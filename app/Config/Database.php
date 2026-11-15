<?php
class Database {
    // =========================================================================
    // KONFIGURASI DATABASE UNTUK CPANEL / SHARED HOSTING (Non-Docker)
    // Silakan sesuaikan 4 baris di bawah ini dengan MySQL Database di cPanel Anda:
    // =========================================================================
    private $cpanelHost = 'localhost';
    private $cpanelName = 'spmb_db';         // Ganti dengan nama database cPanel (misal: username_spmb)
    private $cpanelUser = 'spmb_admin';      // Ganti dengan user database cPanel (misal: username_admin)
    private $cpanelPass = 'SpMb2027Taruna!'; // Ganti dengan password database cPanel
    private $cpanelPort = '3306';

    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $port;
    private $driver = 'mysql';

    // Gunakan koneksi tunggal (Singleton per request) agar tidak melebihi limit koneksi cPanel
    private static $sharedPdo = null;
    private static $sharedMysqli = null;
    private static $schemaChecked = false;

    private $dbh = null;    // PDO instance
    private $stmt = null;   // PDOStatement instance

    private $useMysqli = false;
    private $mysqli = null;
    private $rawQuery = '';
    private $boundParams = [];
    private $lastResultRows = [];
    private $lastAffectedRows = 0;

    public function __construct() {
        $this->host   = getenv('DB_HOST') ?: $this->cpanelHost;
        $this->user   = getenv('DB_USER') ?: $this->cpanelUser;
        $this->pass   = getenv('DB_PASS') ?: $this->cpanelPass;
        $this->dbname = getenv('DB_NAME') ?: $this->cpanelName;
        $this->port   = getenv('DB_PORT') ?: $this->cpanelPort;

        // Jika koneksi sudah dibuat pada request ini, gunakan kembali (hemat koneksi di cPanel)
        if (self::$sharedPdo !== null) {
            $this->dbh = self::$sharedPdo;
            $this->driver = 'mysql';
            return;
        }
        if (self::$sharedMysqli !== null) {
            $this->mysqli = self::$sharedMysqli;
            $this->useMysqli = true;
            $this->driver = 'mysql';
            return;
        }

        // 1. Gunakan PDO MySQL jika ekstensi pdo_mysql aktif
        if (extension_loaded('pdo_mysql')) {
            try {
                $this->driver = 'mysql';
                $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=utf8mb4';
                $options = [
                    PDO::ATTR_PERSISTENT         => false,
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => true
                ];
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
                self::$sharedPdo = $this->dbh;
                return;
            } catch (PDOException $e) {
                die("Koneksi Database MySQL Gagal: " . $e->getMessage() . " — Pastikan nama database, user, dan password di app/Config/Database.php sudah sesuai dengan cPanel.");
            }
        }

        // 2. Fallback Otomatis ke MySQLi Standar (Tanpa ketergantungan mysqlnd / get_result)
        if (extension_loaded('mysqli')) {
            mysqli_report(MYSQLI_REPORT_OFF);
            $conn = @mysqli_connect($this->host, $this->user, $this->pass, $this->dbname, (int) $this->port);
            if (!$conn) {
                die("Koneksi Database MySQL Gagal: " . mysqli_connect_error() . " — Pastikan nama database, user, dan password di app/Config/Database.php sudah sesuai dengan cPanel.");
            }
            mysqli_set_charset($conn, 'utf8mb4');
            $this->useMysqli    = true;
            $this->mysqli       = $conn;
            self::$sharedMysqli = $conn;
            $this->driver       = 'mysql';
            return;
        }

        die("Koneksi Database Gagal: Ekstensi PHP 'pdo_mysql' atau 'mysqli' belum aktif di cPanel.");
    }

    public static function isSchemaChecked(): bool {
        return self::$schemaChecked;
    }

    public static function markSchemaChecked(): void {
        self::$schemaChecked = true;
    }

    public function getDriver(): string {
        return $this->driver;
    }

    /**
     * Menyiapkan SQL query
     */
    public function query(string $query): void {
        if ($this->useMysqli) {
            $this->rawQuery         = $query;
            $this->boundParams      = [];
            $this->lastResultRows   = [];
            $this->lastAffectedRows = 0;
            return;
        }
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * Mengeksekusi perintah DDL / SQL langsung
     */
    public function exec(string $sql) {
        if ($this->useMysqli) {
            $res = mysqli_query($this->mysqli, $sql);
            if ($res === false) {
                throw new Exception(mysqli_error($this->mysqli));
            }
            return mysqli_affected_rows($this->mysqli);
        }
        return $this->dbh->exec($sql);
    }

    /**
     * Mengikat parameter dengan aman
     */
    public function bind(string $param, $value, ?int $type = null): void {
        if ($this->useMysqli) {
            $key = ':' . ltrim($param, ':');
            $this->boundParams[$key] = ['value' => $value, 'type' => $type];
            return;
        }

        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Mengeksekusi query (Kompatibel penuh dengan cPanel PHP-FPM tanpa mysqlnd)
     */
    public function execute(): bool {
        if ($this->useMysqli) {
            $conn   = $this->mysqli;
            $params = $this->boundParams;

            $sql = preg_replace_callback('/:([a-zA-Z0-9_]+)/', function ($matches) use ($conn, $params) {
                $fullKey = $matches[0];
                if (!array_key_exists($fullKey, $params)) {
                    return 'NULL';
                }
                $val  = $params[$fullKey]['value'];
                $type = $params[$fullKey]['type'];

                if ($val === null || $type === PDO::PARAM_NULL) {
                    return 'NULL';
                }
                if (is_bool($val) || $type === PDO::PARAM_BOOL) {
                    return $val ? '1' : '0';
                }
                if (is_int($val) || $type === PDO::PARAM_INT) {
                    return (string) ((int) $val);
                }
                if (is_float($val)) {
                    return (string) $val;
                }
                return "'" . mysqli_real_escape_string($conn, (string) $val) . "'";
            }, $this->rawQuery);

            $result = mysqli_query($conn, $sql);
            if ($result === false) {
                throw new Exception("MySQL Query Error: " . mysqli_error($conn));
            }

            $this->lastAffectedRows = mysqli_affected_rows($conn);
            $this->lastResultRows   = [];

            if ($result instanceof mysqli_result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $this->lastResultRows[] = $row;
                }
                mysqli_free_result($result);
            }

            return true;
        }

        return $this->stmt->execute();
    }

    /**
     * Mengambil ID auto-increment terakhir yang di-insert
     */
    public function lastInsertId(): string {
        if ($this->useMysqli) {
            return (string) mysqli_insert_id($this->mysqli);
        }
        return $this->dbh->lastInsertId();
    }

    /**
     * Mengambil banyak baris data (array asosiatif)
     */
    public function resultSet(): array {
        $this->execute();
        if ($this->useMysqli) {
            return $this->lastResultRows;
        }
        $rows = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->stmt->closeCursor();
        return $rows;
    }

    /**
     * Mengambil satu baris data (array asosiatif atau false jika tidak ditemukan)
     */
    public function single() {
        $this->execute();
        if ($this->useMysqli) {
            return $this->lastResultRows[0] ?? false;
        }
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $this->stmt->closeCursor();
        return $row;
    }

    /**
     * Menghitung jumlah baris yang terpengaruh oleh operasi INSERT, UPDATE, atau DELETE
     */
    public function rowCount(): int {
        if ($this->useMysqli) {
            return (int) $this->lastAffectedRows;
        }
        return $this->stmt->rowCount();
    }

    /**
     * Fungsi bantuan transaksi database
     */
    public function beginTransaction(): bool {
        if ($this->useMysqli) {
            return mysqli_begin_transaction($this->mysqli);
        }
        return $this->dbh->beginTransaction();
    }

    public function commit(): bool {
        if ($this->useMysqli) {
            return mysqli_commit($this->mysqli);
        }
        return $this->dbh->commit();
    }

    public function rollBack(): bool {
        if ($this->useMysqli) {
            return mysqli_rollback($this->mysqli);
        }
        return $this->dbh->rollBack();
    }
}

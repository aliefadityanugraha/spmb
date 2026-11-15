<?php
class UploadService {
    private string $uploadDir;
    private int $maxSizeBytes;
    private array $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png'
    ];
    private array $allowedExtensions = ['jpg', 'jpeg', 'png'];

    public function __construct(?string $uploadDir = null, int $maxSizeKb = 5120) {
        // Default mengarah ke folder public/uploads dengan batas maksimal 5 MB (5120 KB)
        $this->uploadDir    = $uploadDir ?: dirname(__DIR__, 2) . '/public/uploads';
        $this->maxSizeBytes = $maxSizeKb * 1024;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
    }

    /**
     * Memvalidasi (JPG/PNG maks 5MB) dan memindahkan file unggahan ke folder public/uploads
     */
    public function upload(array $file, string $prefix, string $userId): array {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Parameter file unggahan tidak valid.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => $this->getUploadErrorMessage($file['error'])];
        }

        // Validasi ukuran file (Maksimal 5 MB)
        if ($file['size'] <= 0 || $file['size'] > $this->maxSizeBytes) {
            $maxMb = round($this->maxSizeBytes / (1024 * 1024), 1);
            return ['success' => false, 'error' => "Ukuran file melebihi batas maksimal {$maxMb} MB."];
        }

        // Validasi ekstensi file (.jpg, .jpeg, .png)
        $originalName = $file['name'] ?? 'berkas';
        $ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions, true)) {
            return ['success' => false, 'error' => 'Format file tidak didukung. Hanya file gambar JPG, JPEG, atau PNG yang diperbolehkan.'];
        }

        // Validasi MIME Type asli menggunakan finfo
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, $this->allowedMimeTypes)) {
            return ['success' => false, 'error' => 'Konten file tidak valid. Pastikan file adalah gambar JPG atau PNG asli (maksimal 5 MB).'];
        }

        // Buat sub-folder per user agar terorganisir
        $safeUserFolder = preg_replace('/[^a-zA-Z0-9\-]/', '', $userId);
        $targetFolder   = $this->uploadDir . '/' . $safeUserFolder;
        if (!is_dir($targetFolder)) {
            mkdir($targetFolder, 0775, true);
        }

        // Buat nama file unik & aman
        $safePrefix = preg_replace('/[^a-z0-9_]/', '_', strtolower($prefix));
        $finalExt   = $this->allowedMimeTypes[$mimeType];
        $fileName   = sprintf('%s_%s_%s.%s', $safePrefix, date('YmdHis'), bin2hex(random_bytes(4)), $finalExt);
        $fullPath   = $targetFolder . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            return ['success' => false, 'error' => 'Gagal menyimpan file ke server. Periksa izin folder uploads.'];
        }

        $ukuranKb = max(1, (int) ceil($file['size'] / 1024));

        return [
            'success' => true,
            'data'    => [
                'nama_berkas' => $originalName,
                'file_path'   => '/uploads/' . $safeUserFolder . '/' . $fileName,
                'mime_type'   => $mimeType,
                'ukuran_kb'   => $ukuranKb
            ]
        ];
    }

    /**
     * Menghapus file fisik dari folder public/uploads
     */
    public function deleteFile(?string $relativePath): void {
        if (empty($relativePath)) {
            return;
        }
        $publicRoot = dirname(__DIR__, 2) . '/public';
        $fullPath   = $publicRoot . '/' . ltrim($relativePath, '/');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function getUploadErrorMessage(int $errorCode): string {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Ukuran file melebihi batas maksimal 5 MB.';
            case UPLOAD_ERR_PARTIAL:
                return 'File hanya terunggah sebagian. Silakan coba lagi.';
            case UPLOAD_ERR_NO_FILE:
                return 'Tidak ada file yang dipilih.';
            default:
                return 'Terjadi kesalahan saat mengunggah file.';
        }
    }
}

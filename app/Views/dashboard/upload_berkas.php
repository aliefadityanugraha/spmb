<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Unggah Berkas Persyaratan Wajib & Sertifikat Prestasi Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
    $data['meta_robots']      = 'noindex, nofollow';
    require __DIR__ . '/../partials/seo_head.php';
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col">

    <?php
    $berkas              = $data['berkas'] ?? [];
    $errors              = $data['errors'] ?? [];
    $isBeasiswa          = !empty($data['is_beasiswa']);
    $sertifikatTersimpan = $berkas['sertifikat'] ?? [];
    $jumlahSertifikat    = count($sertifikatTersimpan);
    $sisaKuotaSertifikat = max(0, 5 - $jumlahSertifikat);
    ?>

    <!-- Navbar Atas Responsif -->
    <header class="bg-white/95 border-b border-slate-200 sticky top-0 z-30 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 sm:h-16 flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 min-w-0">
                <a href="/dashboard" class="shrink-0">
                    <img src="/assets/img/pentab.png" alt="SPMB SMAN 5 Taruna Brawijaya" class="h-9 sm:h-11 w-auto object-contain">
                </a>
                <div class="hidden sm:block truncate border-l border-slate-200 pl-3">
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight truncate">SMAN 5 Taruna Brawijaya</h1>
                    <p class="text-[11px] text-slate-500 truncate">Portal Pendaftaran Calon Taruna</p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <?= htmlspecialchars($data['user']['jalur_pendaftaran'] ?? '') ?>
                </span>

                <a href="/dashboard" class="hidden sm:inline-flex px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 transition">
                    &larr; Dasbor
                </a>
                <a href="/auth/logout" class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition">
                    Keluar
                </a>
            </div>
        </div>

        <!-- Navigasi Cepat Horizontal Khusus Mobile / Tablet (< lg) -->
        <div class="lg:hidden border-t border-slate-100 bg-slate-50/90 px-3 py-2 overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-1.5 min-w-max">
                <a href="/dashboard" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    Dasbor
                </a>
                <a href="/biodata" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    1. Biodata
                </a>
                <a href="/berkas" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm">
                    2. Berkas
                </a>
                <a href="/nilai" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    3. Nilai
                </a>
                <a href="/cetak/rekap" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    4. Cetak Rekap
                </a>
                <a href="/cetak/kartu" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    5. Cetak Kartu
                </a>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">

            <!-- Sidebar 5 Menu Navigasi (Tampil di Desktop lg+) -->
            <aside class="hidden lg:block lg:col-span-1 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm sticky top-24">
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                        Menu Pendaftaran
                    </p>
                    <nav class="space-y-1.5">
                        <a href="/dashboard" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>Ringkasan Dasbor</span>
                        </a>
                        <a href="/biodata" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>1. Biodata</span>
                        </a>
                        <a href="/berkas" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span>2. Upload Berkas</span>
                        </a>
                        <a href="/nilai" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>3. Nilai Raport</span>
                        </a>
                        <a href="/cetak/rekap" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>4. Cetak Rekap</span>
                        </a>
                        <a href="/cetak/kartu" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>5. Cetak Kartu</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Area Form Upload Berkas -->
            <section class="lg:col-span-3 space-y-6">

                <!-- Header Halaman -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-xl font-extrabold text-slate-900">Unggah Berkas Persyaratan</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <?= htmlspecialchars($data['user']['jalur_pendaftaran'] ?? '') ?>
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Format file yang wajib digunakan: <strong>JPG, JPEG, atau PNG</strong> dengan ukuran maksimal <strong>5 MB</strong> per file.
                        </p>
                    </div>
                </div>

                <!-- Notifikasi Flash -->
                <?php if (!empty($data['success'])): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 font-medium flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><?= htmlspecialchars($data['success']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['error'])): ?>
                    <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-700 font-medium">
                        <?= htmlspecialchars($data['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="/berkas/simpan" method="POST" enctype="multipart/form-data" class="space-y-6">

                    <!-- ========================================================= -->
                    <!-- BAGIAN 1: DOKUMEN PERSYARATAN UTAMA                       -->
                    <!-- ========================================================= -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-emerald-700">A. Dokumen Wajib Calon Taruna</h3>
                            <p class="text-xs text-slate-500">Unggah dokumen asli hasil scan yang jelas dan terbaca. Jika sudah pernah mengunggah, memilih file baru akan menggantikan file lama.</p>
                        </div>

                        <?php
                        $daftarDokumenWajib = [
                            'kk' => [
                                'label' => '1. Kartu Keluarga (KK)',
                                'ket'   => 'Scan Kartu Keluarga asli yang masih berlaku.'
                            ],
                            'keterangan_sehat' => [
                                'label' => '2. Surat Keterangan Sehat',
                                'ket'   => 'Surat keterangan sehat dari Puskesmas / Rumah Sakit Pemerintah.'
                            ],
                            'kelakuan_baik' => [
                                'label' => '3. Surat Keterangan Kelakuan Baik',
                                'ket'   => 'Surat keterangan berkelakuan baik dari sekolah asal (SMP/MTs) atau SKCK.'
                            ],
                            'ijazah' => [
                                'label' => '4. Ijazah / Surat Keterangan Lulus (SKL)',
                                'ket'   => 'Scan Ijazah SMP/MTs atau Surat Keterangan Lulus sementara.'
                            ]
                        ];
                        ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <?php foreach ($daftarDokumenWajib as $key => $info): ?>
                                <?php $existingDoc = $berkas[$key] ?? null; ?>
                                <div class="p-4 rounded-xl bg-slate-50/70 border <?= isset($errors[$key]) ? 'border-rose-500' : 'border-slate-200' ?> flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <label for="<?= $key ?>" class="text-sm font-bold text-slate-800">
                                                <?= htmlspecialchars($info['label']) ?>
                                            </label>
                                            <?php if ($existingDoc): ?>
                                                <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    Sudah Diunggah
                                                </span>
                                            <?php else: ?>
                                                <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    Belum Ada
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($info['ket']) ?></p>

                                        <?php if ($existingDoc): ?>
                                            <div class="mt-2.5 p-2.5 rounded-lg bg-white border border-slate-200 flex items-center justify-between gap-2 text-xs">
                                                <span class="truncate text-slate-700 font-medium" title="<?= htmlspecialchars($existingDoc['nama_berkas']) ?>">
                                                    <?= htmlspecialchars($existingDoc['nama_berkas']) ?> (<?= (int)$existingDoc['ukuran_kb'] ?> KB)
                                                </span>
                                                <a href="<?= htmlspecialchars($existingDoc['file_path']) ?>" target="_blank" class="shrink-0 font-bold text-emerald-700 hover:underline">
                                                    Lihat File
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div>
                                        <input
                                            id="<?= $key ?>"
                                            name="<?= $key ?>"
                                            type="file"
                                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                            onchange="validasiFileBerkas(this)"
                                            class="input-file-berkas block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer bg-white rounded-xl border border-slate-300 p-1.5"
                                        >
                                        <p class="pesan-error-client hidden mt-1.5 text-xs text-rose-600 font-medium"></p>
                                        <p class="mt-1 text-[11px] text-slate-400">Format: JPG / PNG &bull; Maks: 5 MB</p>
                                        <?php if (isset($errors[$key])): ?>
                                            <p class="mt-1.5 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors[$key]) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- BAGIAN 2: SERTIFIKAT PRESTASI (BEASISWA & UMUM OPSIONAL)  -->
                    <!-- ========================================================= -->
                    <div class="bg-white border <?= $isBeasiswa ? 'border-emerald-300' : 'border-slate-200' ?> rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-bold text-emerald-700">
                                        B. Sertifikat Prestasi <?= $isBeasiswa ? '(Jalur Beasiswa)' : '(Opsional - Jalur Umum)' ?>
                                    </h3>
                                    <?php if (!$isBeasiswa): ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Opsional / Tidak Wajib
                                        </span>
                                    <?php endif; ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <?= $jumlahSertifikat ?> / 5 Terunggah
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    <?php if ($isBeasiswa): ?>
                                        Unggah maksimal <strong>5 sertifikat prestasi</strong> terbaik Anda beserta tingkat kejuaraannya (Kota / Provinsi / Nasional).
                                    <?php else: ?>
                                        Bagi pendaftar <strong>Jalur Umum</strong>, unggah sertifikat prestasi bersifat <strong>opsional</strong> (nilai tambah jika memiliki). Kosongkan jika tidak ada.
                                    <?php endif; ?>
                                </p>
                            </div>

                            <?php if ($sisaKuotaSertifikat > 0): ?>
                                <button
                                    type="button"
                                    id="btn-tambah-sertifikat"
                                    onclick="tambahBarisSertifikat()"
                                    class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <?= $isBeasiswa ? 'Tambah Sertifikat' : 'Tambah Sertifikat (Opsional)' ?>
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (isset($errors['sertifikat'])): ?>
                            <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-700 font-medium">
                                <?= htmlspecialchars($errors['sertifikat']) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Daftar Sertifikat yang Sudah Tersimpan di Database -->
                        <?php if (!empty($sertifikatTersimpan)): ?>
                            <div class="space-y-2.5">
                                <p class="text-xs font-semibold text-slate-700">Sertifikat yang Sudah Diunggah:</p>
                                <?php foreach ($sertifikatTersimpan as $idx => $sert): ?>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-6 h-6 rounded-lg bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center shrink-0">
                                                <?= $idx + 1 ?>
                                            </span>
                                            <div class="truncate">
                                                <p class="font-bold text-slate-800 truncate"><?= htmlspecialchars($sert['nama_berkas']) ?></p>
                                                <p class="text-slate-500">
                                                    Tingkat: <strong class="text-emerald-700"><?= htmlspecialchars($sert['tingkat'] ?? '-') ?></strong>
                                                    &bull; <?= (int)$sert['ukuran_kb'] ?> KB
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <a href="<?= htmlspecialchars($sert['file_path']) ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-emerald-700 font-bold hover:bg-emerald-50">
                                                Lihat File
                                            </a>
                                            <button
                                                type="button"
                                                onclick="hapusSertifikatTersimpan(<?= (int)$sert['id'] ?>)"
                                                class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-semibold hover:bg-rose-100"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Container Input Dinamis Sertifikat Baru -->
                        <?php if ($sisaKuotaSertifikat > 0): ?>
                            <div id="sertifikat-container" class="space-y-3">
                                <!-- Baris dinamis ditambahkan melalui JavaScript -->
                            </div>
                            <p id="kuota-info" class="text-xs text-slate-500">
                                Sisa kuota sertifikat yang dapat ditambahkan: <strong id="sisa-kuota-teks" class="text-emerald-700"><?= $sisaKuotaSertifikat ?></strong> dari maksimal 5 sertifikat.
                            </p>
                        <?php else: ?>
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                                Anda telah mencapai batas maksimal 5 sertifikat prestasi. Hapus salah satu sertifikat di atas jika ingin menggantinya dengan file baru.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-2">
                        <a href="/dashboard" class="w-full sm:w-auto text-center px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Kembali ke Dasbor
                        </a>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-xl shadow-md shadow-emerald-600/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
                        >
                            Simpan &amp; Unggah Berkas
                        </button>
                    </div>
                </form>

                <!-- Form tersembunyi untuk aksi hapus sertifikat tersimpan -->
                <form id="form-hapus-sertifikat" method="POST" class="hidden"></form>

            </section>
        </div>
    </div>

    <script>
        const isBeasiswa = <?= $isBeasiswa ? 'true' : 'false' ?>;
        const maxSertifikatTotal = 5;
        const sudahTerunggah = <?= (int) $jumlahSertifikat ?>;
        const maxBisaDitambah = Math.max(0, maxSertifikatTotal - sudahTerunggah);
        let jumlahBarisAktif = 0;

        function updateStatusTombol() {
            const btnTambah = document.getElementById('btn-tambah-sertifikat');
            const sisaTeks = document.getElementById('sisa-kuota-teks');
            const sisa = Math.max(0, maxBisaDitambah - jumlahBarisAktif);

            if (sisaTeks) {
                sisaTeks.textContent = sisa;
            }
            if (btnTambah) {
                if (jumlahBarisAktif >= maxBisaDitambah) {
                    btnTambah.disabled = true;
                    btnTambah.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnTambah.disabled = false;
                    btnTambah.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        function tambahBarisSertifikat() {
            if (jumlahBarisAktif >= maxBisaDitambah) {
                alert('Maksimal 5 sertifikat prestasi yang dapat diunggah.');
                return;
            }

            const container = document.getElementById('sertifikat-container');
            if (!container) return;

            jumlahBarisAktif++;
            const nomorUrut = sudahTerunggah + jumlahBarisAktif;

            const row = document.createElement('div');
            row.className = 'sertifikat-item p-4 rounded-xl bg-emerald-50/40 border border-emerald-200 space-y-3';
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">
                        Sertifikat Prestasi #${nomorUrut}
                    </span>
                    <button type="button" onclick="hapusBarisSertifikat(this)" class="text-xs font-semibold text-rose-600 hover:text-rose-700">
                        Hapus Baris
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lomba / Kejuaraan</label>
                        <input
                            type="text"
                            name="sertifikat_nama[]"
                            placeholder="Contoh: Juara 1 OSN Matematika"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 focus:border-emerald-600 focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat Kejuaraan <span class="text-emerald-600">*</span></label>
                        <select
                            name="sertifikat_tingkat[]"
                            required
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 focus:border-emerald-600 focus:outline-none"
                        >
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="Kota">Kota / Kabupaten</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Nasional">Nasional</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">File Sertifikat (JPG/PNG Maks 5MB) <span class="text-emerald-600">*</span></label>
                        <input
                            type="file"
                            name="sertifikat_file[]"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            onchange="validasiFileBerkas(this)"
                            required
                            class="input-file-berkas block w-full text-xs text-slate-600 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer bg-white rounded-xl border border-slate-300 p-1"
                        >
                        <p class="pesan-error-client hidden mt-1 text-xs text-rose-600 font-medium"></p>
                    </div>
                </div>
            `;

            container.appendChild(row);
            updateStatusTombol();
        }

        function hapusBarisSertifikat(btn) {
            const item = btn.closest('.sertifikat-item');
            if (item) {
                item.remove();
                jumlahBarisAktif = Math.max(0, jumlahBarisAktif - 1);
                updateStatusTombol();
            }
        }

        function hapusSertifikatTersimpan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')) {
                const form = document.getElementById('form-hapus-sertifikat');
                form.action = '/berkas/hapus/' + id;
                form.submit();
            }
        }

        /**
         * Validasi Client-Side: Hanya mengizinkan gambar JPG/JPEG/PNG dengan ukuran maksimal 5 MB
         */
        function validasiFileBerkas(input) {
            const maxBytes = 5 * 1024 * 1024; // 5 MB
            const allowedTypes = ['image/jpeg', 'image/png'];
            const allowedExts = ['jpg', 'jpeg', 'png'];
            const errorEl = input.parentElement.querySelector('.pesan-error-client');

            if (errorEl) {
                errorEl.classList.add('hidden');
                errorEl.textContent = '';
            }
            input.classList.remove('border-rose-500');

            if (!input.files || !input.files[0]) {
                return true;
            }

            const file = input.files[0];
            const fileName = file.name || '';
            const ext = fileName.split('.').pop().toLowerCase();

            if (!allowedTypes.includes(file.type) || !allowedExts.includes(ext)) {
                const msg = 'Format file tidak valid! Hanya file gambar JPG atau PNG yang diperbolehkan.';
                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                } else {
                    alert(msg);
                }
                input.classList.add('border-rose-500');
                input.value = '';
                return false;
            }

            if (file.size > maxBytes) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                const msg = `Ukuran file terlalu besar (${sizeMb} MB). Maksimal ukuran file adalah 5 MB.`;
                if (errorEl) {
                    errorEl.textContent = msg;
                    errorEl.classList.remove('hidden');
                } else {
                    alert(msg);
                }
                input.classList.add('border-rose-500');
                input.value = '';
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (isBeasiswa && sudahTerunggah === 0 && maxBisaDitambah > 0) {
                tambahBarisSertifikat();
            }
        });
    </script>

</body>
</html>

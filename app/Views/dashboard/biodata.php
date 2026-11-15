<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Formulir Biodata Diri & Unggah Pas Foto 3x4 Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
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
    $bio        = $data['biodata'] ?? [];
    $errors     = $data['errors'] ?? [];
    $isEditMode = !empty($data['is_edit_mode']);
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
                <?php if (!empty($data['user']['jalur_pendaftaran'])): ?>
                    <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <?= htmlspecialchars($data['user']['jalur_pendaftaran']) ?>
                    </span>
                <?php endif; ?>

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
                <a href="/biodata" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm">
                    1. Biodata
                </a>
                <a href="/berkas" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
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
                        <a href="/biodata" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span>1. Biodata</span>
                            <span class="w-2 h-2 rounded-full <?= $isEditMode ? 'bg-emerald-500' : 'bg-amber-500' ?>"></span>
                        </a>
                        <a href="/berkas" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
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

            <!-- Form Biodata -->
            <section class="lg:col-span-3 space-y-6">

                <!-- Header Status Form -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-xl font-extrabold text-slate-900">Formulir Biodata Calon Taruna</h2>
                            <?php if ($isEditMode): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Mode Edit (Data Tersimpan)
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Belum Dilengkapi
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            <?= $isEditMode
                                ? 'Data biodata Anda sudah tersimpan. Anda dapat memperbarui isian di bawah ini kapan saja sebelum finalisasi.'
                                : 'Silakan lengkapi seluruh data diri, pas foto, sekolah asal, orang tua, dan alamat domisili Anda.' ?>
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

                <form action="/biodata/simpan" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate>

                    <!-- ========================================================= -->
                    <!-- BAGIAN 1: DATA DIRI & UPLOAD PAS FOTO CALON TARUNA        -->
                    <!-- ========================================================= -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-emerald-700">A. Identitas, Data Diri, &amp; Pas Foto</h3>
                            <p class="text-xs text-slate-500">Sesuaikan dengan Akta Kelahiran dan Kartu Keluarga, serta unggah pas foto resmi ukuran 3&times;4 cm.</p>
                        </div>

                        <!-- Kolom Upload Pas Foto 3x4 -->
                        <div class="p-4 rounded-xl bg-emerald-50/40 border <?= isset($errors['pas_foto']) ? 'border-rose-500' : 'border-emerald-200/80' ?> flex flex-col sm:flex-row items-center gap-5">
                            <div class="w-28 h-36 rounded-xl border-2 border-dashed border-emerald-300 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-sm">
                                <?php if (!empty($bio['pas_foto'])): ?>
                                    <img id="preview-pas-foto" src="<?= htmlspecialchars($bio['pas_foto']) ?>" alt="Pas Foto Calon Taruna" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img id="preview-pas-foto" src="" alt="Preview Pas Foto" loading="lazy" decoding="async" class="w-full h-full object-cover hidden">
                                    <div id="placeholder-pas-foto" class="text-center p-2">
                                        <span class="text-xs font-bold text-slate-500 block">Pas Foto</span>
                                        <span class="text-[11px] text-emerald-700 font-semibold">3 &times; 4 cm</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 w-full space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label for="pas_foto" class="text-sm font-bold text-slate-800">
                                        Upload Pas Foto Resmi (3&times;4) <span class="text-emerald-600">*</span>
                                    </label>
                                    <?php if (!empty($bio['pas_foto'])): ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Foto Tersimpan
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-600">
                                    Format gambar <strong>JPG, JPEG, atau PNG</strong> (maksimal <strong>5 MB</strong>). Berseragam sekolah asal dengan latar belakang merah/biru.
                                    <?= !empty($bio['pas_foto']) ? 'Kosongkan jika tidak ingin mengganti pas foto.' : '' ?>
                                </p>
                                <input
                                    id="pas_foto"
                                    name="pas_foto"
                                    type="file"
                                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                    onchange="tampilkanPreviewFoto(this)"
                                    class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer bg-white rounded-xl border border-slate-300 p-1.5"
                                >
                                <?php if (isset($errors['pas_foto'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['pas_foto']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- NIK (Readonly dari tabel users) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500">NIK (16 Digit)</label>
                                <input
                                    type="text"
                                    disabled
                                    value="<?= htmlspecialchars($data['user']['nik'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 sm:text-sm cursor-not-allowed"
                                >
                            </div>

                            <!-- NISN (Readonly dari tabel users) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500">NISN (10 Digit)</label>
                                <input
                                    type="text"
                                    disabled
                                    value="<?= htmlspecialchars($data['user']['nisn'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 sm:text-sm cursor-not-allowed"
                                >
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-semibold text-slate-700">
                                Nama Lengkap <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                id="nama_lengkap"
                                name="nama_lengkap"
                                type="text"
                                required
                                value="<?= htmlspecialchars($bio['nama_lengkap'] ?? '') ?>"
                                class="mt-1.5 block w-full rounded-xl border <?= isset($errors['nama_lengkap']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                            >
                            <?php if (isset($errors['nama_lengkap'])): ?>
                                <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['nama_lengkap']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-semibold text-slate-700">
                                    Tempat Lahir <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="tempat_lahir"
                                    name="tempat_lahir"
                                    type="text"
                                    required
                                    placeholder="Contoh: Kediri"
                                    value="<?= htmlspecialchars($bio['tempat_lahir'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['tempat_lahir']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['tempat_lahir'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['tempat_lahir']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700">
                                    Tanggal Lahir <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="tanggal_lahir"
                                    name="tanggal_lahir"
                                    type="date"
                                    required
                                    value="<?= htmlspecialchars($bio['tanggal_lahir'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['tanggal_lahir']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['tanggal_lahir'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['tanggal_lahir']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700">
                                    Jenis Kelamin <span class="text-emerald-600">*</span>
                                </label>
                                <select
                                    id="jenis_kelamin"
                                    name="jenis_kelamin"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['jenis_kelamin']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" <?= (($bio['jenis_kelamin'] ?? '') === 'L') ? 'selected' : '' ?>>Laki-laki (L)</option>
                                    <option value="P" <?= (($bio['jenis_kelamin'] ?? '') === 'P') ? 'selected' : '' ?>>Perempuan (P)</option>
                                </select>
                                <?php if (isset($errors['jenis_kelamin'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['jenis_kelamin']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Agama -->
                            <div>
                                <label for="agama" class="block text-sm font-semibold text-slate-700">
                                    Agama <span class="text-emerald-600">*</span>
                                </label>
                                <?php $agamaList = ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; ?>
                                <select
                                    id="agama"
                                    name="agama"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['agama']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                    <option value="">-- Pilih Agama --</option>
                                    <?php foreach ($agamaList as $agm): ?>
                                        <option value="<?= $agm ?>" <?= (($bio['agama'] ?? '') === $agm) ? 'selected' : '' ?>>
                                            <?= $agm ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['agama'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['agama']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- BAGIAN 2: DATA SEKOLAH ASAL (SMP/MTs)                     -->
                    <!-- ========================================================= -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-emerald-700">B. Data Sekolah Asal (SMP / MTs Sederajat)</h3>
                            <p class="text-xs text-slate-500">Masukkan NPSN dan nama sekolah asal Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <!-- NPSN Sekolah Asal -->
                            <div>
                                <label for="npsn_sekolah_asal" class="block text-sm font-semibold text-slate-700">
                                    NPSN Sekolah Asal (8 Digit)
                                </label>
                                <input
                                    id="npsn_sekolah_asal"
                                    name="npsn_sekolah_asal"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="8"
                                    placeholder="Contoh: 20534312"
                                    value="<?= htmlspecialchars($bio['npsn_sekolah_asal'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['npsn_sekolah_asal']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['npsn_sekolah_asal'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['npsn_sekolah_asal']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Nama Sekolah Asal -->
                            <div>
                                <label for="nama_sekolah_asal" class="block text-sm font-semibold text-slate-700">
                                    Nama Sekolah Asal <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="nama_sekolah_asal"
                                    name="nama_sekolah_asal"
                                    type="text"
                                    required
                                    placeholder="Contoh: SMP Negeri 1 Kediri"
                                    value="<?= htmlspecialchars($bio['nama_sekolah_asal'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['nama_sekolah_asal']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['nama_sekolah_asal'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['nama_sekolah_asal']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Tahun Lulus -->
                            <div>
                                <label for="tahun_lulus" class="block text-sm font-semibold text-slate-700">
                                    Tahun Lulus <span class="text-emerald-600">*</span>
                                </label>
                                <?php $maxGradYear = 2027; ?>
                                <select
                                    id="tahun_lulus"
                                    name="tahun_lulus"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['tahun_lulus']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                    <?php for ($yr = $maxGradYear; $yr >= $maxGradYear - 2; $yr--): ?>
                                        <option value="<?= $yr ?>" <?= ((int)($bio['tahun_lulus'] ?? 2027) === $yr) ? 'selected' : '' ?>>
                                            <?= $yr ?><?= $yr === 2027 ? ' (Kelas IX TA 2026/2027)' : ' (Lulusan Tahun Sebelumnya)' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (isset($errors['tahun_lulus'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['tahun_lulus']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- BAGIAN 3: DATA ORANG TUA / WALI                           -->
                    <!-- ========================================================= -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-emerald-700">C. Data Orang Tua / Wali</h3>
                            <p class="text-xs text-slate-500">Informasi orang tua kandung atau wali murid yang dapat dihubungi.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Nama Ayah -->
                            <div>
                                <label for="nama_ayah" class="block text-sm font-semibold text-slate-700">Nama Ayah</label>
                                <input
                                    id="nama_ayah"
                                    name="nama_ayah"
                                    type="text"
                                    placeholder="Nama lengkap ayah"
                                    value="<?= htmlspecialchars($bio['nama_ayah'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- Pekerjaan Ayah -->
                            <div>
                                <label for="pekerjaan_ayah" class="block text-sm font-semibold text-slate-700">Pekerjaan Ayah</label>
                                <input
                                    id="pekerjaan_ayah"
                                    name="pekerjaan_ayah"
                                    type="text"
                                    placeholder="Contoh: PNS / TNI / Polri / Wiraswasta"
                                    value="<?= htmlspecialchars($bio['pekerjaan_ayah'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- Nama Ibu -->
                            <div>
                                <label for="nama_ibu" class="block text-sm font-semibold text-slate-700">
                                    Nama Ibu Kandung <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="nama_ibu"
                                    name="nama_ibu"
                                    type="text"
                                    required
                                    placeholder="Nama lengkap ibu kandung"
                                    value="<?= htmlspecialchars($bio['nama_ibu'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['nama_ibu']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['nama_ibu'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['nama_ibu']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Pekerjaan Ibu -->
                            <div>
                                <label for="pekerjaan_ibu" class="block text-sm font-semibold text-slate-700">Pekerjaan Ibu</label>
                                <input
                                    id="pekerjaan_ibu"
                                    name="pekerjaan_ibu"
                                    type="text"
                                    placeholder="Contoh: Ibu Rumah Tangga / Guru / Wiraswasta"
                                    value="<?= htmlspecialchars($bio['pekerjaan_ibu'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <!-- Nama Wali (Opsional) -->
                            <div>
                                <label for="nama_wali" class="block text-sm font-semibold text-slate-700">Nama Wali (Opsional)</label>
                                <input
                                    id="nama_wali"
                                    name="nama_wali"
                                    type="text"
                                    placeholder="Kosongkan jika tidak ada"
                                    value="<?= htmlspecialchars($bio['nama_wali'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- Nomor HP Orang Tua -->
                            <div>
                                <label for="no_hp_ortu" class="block text-sm font-semibold text-slate-700">
                                    No. HP / WA Orang Tua <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="no_hp_ortu"
                                    name="no_hp_ortu"
                                    type="tel"
                                    inputmode="numeric"
                                    maxlength="15"
                                    required
                                    placeholder="Contoh: 081234567890"
                                    value="<?= htmlspecialchars($bio['no_hp_ortu'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['no_hp_ortu']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['no_hp_ortu'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['no_hp_ortu']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Penghasilan Orang Tua -->
                            <div>
                                <label for="penghasilan_ortu" class="block text-sm font-semibold text-slate-700">Penghasilan Gabungan Ortu</label>
                                <?php
                                $penghasilanOpsi = [
                                    '< Rp 2.000.000',
                                    'Rp 2.000.000 - Rp 4.000.000',
                                    'Rp 4.000.000 - Rp 7.000.000',
                                    '> Rp 7.000.000'
                                ];
                                ?>
                                <select
                                    id="penghasilan_ortu"
                                    name="penghasilan_ortu"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                    <option value="">-- Pilih Rentang Penghasilan --</option>
                                    <?php foreach ($penghasilanOpsi as $opsi): ?>
                                        <option value="<?= htmlspecialchars($opsi) ?>" <?= (($bio['penghasilan_ortu'] ?? '') === $opsi) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($opsi) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- BAGIAN 4: ALAMAT DOMISILI LENGKAP                         -->
                    <!-- ========================================================= -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-emerald-700">D. Alamat Domisili Lengkap</h3>
                            <p class="text-xs text-slate-500">Sesuai Kartu Keluarga (KK) calon taruna.</p>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="alamat_lengkap" class="block text-sm font-semibold text-slate-700">
                                Alamat Lengkap (Nama Jalan / Dusun / Nomor Rumah) <span class="text-emerald-600">*</span>
                            </label>
                            <textarea
                                id="alamat_lengkap"
                                name="alamat_lengkap"
                                rows="3"
                                required
                                placeholder="Contoh: Jl. Selomangleng No. 2, Dusun Sukorame"
                                class="mt-1.5 block w-full rounded-xl border <?= isset($errors['alamat_lengkap']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                            ><?= htmlspecialchars($bio['alamat_lengkap'] ?? '') ?></textarea>
                            <?php if (isset($errors['alamat_lengkap'])): ?>
                                <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['alamat_lengkap']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <!-- RT -->
                            <div>
                                <label for="rt" class="block text-sm font-semibold text-slate-700">RT</label>
                                <input
                                    id="rt"
                                    name="rt"
                                    type="text"
                                    maxlength="5"
                                    placeholder="001"
                                    value="<?= htmlspecialchars($bio['rt'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- RW -->
                            <div>
                                <label for="rw" class="block text-sm font-semibold text-slate-700">RW</label>
                                <input
                                    id="rw"
                                    name="rw"
                                    type="text"
                                    maxlength="5"
                                    placeholder="002"
                                    value="<?= htmlspecialchars($bio['rw'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- Kelurahan / Desa -->
                            <div class="col-span-2">
                                <label for="kelurahan_desa" class="block text-sm font-semibold text-slate-700">
                                    Kelurahan / Desa <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="kelurahan_desa"
                                    name="kelurahan_desa"
                                    type="text"
                                    required
                                    placeholder="Nama Kelurahan atau Desa"
                                    value="<?= htmlspecialchars($bio['kelurahan_desa'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['kelurahan_desa']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['kelurahan_desa'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['kelurahan_desa']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Kecamatan -->
                            <div>
                                <label for="kecamatan" class="block text-sm font-semibold text-slate-700">
                                    Kecamatan <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="kecamatan"
                                    name="kecamatan"
                                    type="text"
                                    required
                                    placeholder="Nama Kecamatan"
                                    value="<?= htmlspecialchars($bio['kecamatan'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['kecamatan']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['kecamatan'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['kecamatan']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Kabupaten / Kota -->
                            <div>
                                <label for="kabupaten_kota" class="block text-sm font-semibold text-slate-700">
                                    Kabupaten / Kota <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="kabupaten_kota"
                                    name="kabupaten_kota"
                                    type="text"
                                    required
                                    placeholder="Contoh: Kota Kediri"
                                    value="<?= htmlspecialchars($bio['kabupaten_kota'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['kabupaten_kota']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['kabupaten_kota'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['kabupaten_kota']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label for="provinsi" class="block text-sm font-semibold text-slate-700">
                                    Provinsi <span class="text-emerald-600">*</span>
                                </label>
                                <input
                                    id="provinsi"
                                    name="provinsi"
                                    type="text"
                                    required
                                    placeholder="Contoh: Jawa Timur"
                                    value="<?= htmlspecialchars($bio['provinsi'] ?? 'Jawa Timur') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['provinsi']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['provinsi'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['provinsi']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Kode Pos -->
                            <div>
                                <label for="kode_pos" class="block text-sm font-semibold text-slate-700">Kode Pos (5 Digit)</label>
                                <input
                                    id="kode_pos"
                                    name="kode_pos"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="5"
                                    placeholder="Contoh: 64114"
                                    value="<?= htmlspecialchars($bio['kode_pos'] ?? '') ?>"
                                    class="mt-1.5 block w-full rounded-xl border <?= isset($errors['kode_pos']) ? 'border-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                                <?php if (isset($errors['kode_pos'])): ?>
                                    <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors['kode_pos']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Latitude (Opsional) -->
                            <div>
                                <label for="latitude" class="block text-sm font-semibold text-slate-700">Latitude (Opsional)</label>
                                <input
                                    id="latitude"
                                    name="latitude"
                                    type="text"
                                    placeholder="-7.8012345"
                                    value="<?= htmlspecialchars((string)($bio['latitude'] ?? '')) ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>

                            <!-- Longitude (Opsional) -->
                            <div>
                                <label for="longitude" class="block text-sm font-semibold text-slate-700">Longitude (Opsional)</label>
                                <input
                                    id="longitude"
                                    name="longitude"
                                    type="text"
                                    placeholder="111.9812345"
                                    value="<?= htmlspecialchars((string)($bio['longitude'] ?? '')) ?>"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-emerald-600 bg-slate-50/60 focus:bg-white px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 sm:text-sm transition"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-2">
                        <a href="/dashboard" class="w-full sm:w-auto text-center px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Kembali ke Dasbor
                        </a>
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-xl shadow-md shadow-emerald-600/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
                        >
                            <?= $isEditMode ? 'Perbarui Data Biodata' : 'Simpan Data Biodata' ?>
                        </button>
                    </div>
                </form>

            </section>
        </div>
    </div>

    <script>
        function tampilkanPreviewFoto(input) {
            const preview = document.getElementById('preview-pas-foto');
            const placeholder = document.getElementById('placeholder-pas-foto');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxBytes = 5 * 1024 * 1024; // 5 MB
                const allowedTypes = ['image/jpeg', 'image/png'];

                if (!allowedTypes.includes(file.type)) {
                    alert('Format pas foto tidak valid! Hanya file JPG atau PNG yang diperbolehkan.');
                    input.value = '';
                    return;
                }
                if (file.size > maxBytes) {
                    alert('Ukuran pas foto melebihi batas maksimal 5 MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>

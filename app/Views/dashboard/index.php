<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Dasbor Pendaftaran Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur — Kelola biodata, berkas persyaratan, nilai rapor, dan cetak kartu peserta.';
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

            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <?php if (!empty($data['user']['jalur_pendaftaran'])): ?>
                    <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <?= htmlspecialchars($data['user']['jalur_pendaftaran']) ?>
                    </span>
                <?php endif; ?>

                <div class="hidden sm:block text-right">
                    <p class="text-xs font-bold text-slate-800 truncate max-w-[160px]"><?= htmlspecialchars($data['user']['nama_lengkap'] ?? '') ?></p>
                    <p class="text-[11px] text-slate-500">NISN: <?= htmlspecialchars($data['user']['nisn'] ?? '-') ?></p>
                </div>

                <a href="/auth/logout" class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition">
                    Keluar
                </a>
            </div>
        </div>

        <?php
        $s1Done = !empty($data['stats']['biodata_lengkap']);
        $s2Done = !empty($data['stats']['berkas_lengkap']);
        $s3Done = !empty($data['stats']['nilai_lengkap']);
        $s4Done = !empty($data['stats']['rekap_selesai']);
        ?>
        <?php if (empty($data['wajib_pilih_jalur'])): ?>
            <!-- Navigasi Cepat Horizontal Khusus Mobile / Tablet (< lg) -->
            <div class="lg:hidden border-t border-slate-100 bg-slate-50/90 px-3 py-2 overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-1.5 min-w-max">
                    <a href="/dashboard" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm">
                        Dasbor
                    </a>
                    <a href="/biodata" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                        1. Biodata <?= $s1Done ? '&#10003;' : '' ?>
                    </a>
                    <a href="<?= $s1Done ? '/berkas' : '/biodata' ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $s1Done ? 'bg-white text-slate-700 border-slate-200' : 'bg-slate-100 text-slate-400 border-slate-200' ?> border">
                        2. Berkas <?= !$s1Done ? '&#128274;' : ($s2Done ? '&#10003;' : '') ?>
                    </a>
                    <a href="<?= $s2Done ? '/nilai' : ($s1Done ? '/berkas' : '/biodata') ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $s2Done ? 'bg-white text-slate-700 border-slate-200' : 'bg-slate-100 text-slate-400 border-slate-200' ?> border">
                        3. Nilai <?= !$s2Done ? '&#128274;' : ($s3Done ? '&#10003;' : '') ?>
                    </a>
                    <a href="<?= $s3Done ? '/cetak/rekap' : ($s2Done ? '/nilai' : ($s1Done ? '/berkas' : '/biodata')) ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $s3Done ? 'bg-white text-slate-700 border-slate-200' : 'bg-slate-100 text-slate-400 border-slate-200' ?> border">
                        4. Cetak Rekap <?= !$s3Done ? '&#128274;' : ($s4Done ? '&#10003;' : '') ?>
                    </a>
                    <a href="<?= $s4Done ? '/cetak/kartu' : ($s3Done ? '/cetak/rekap' : ($s2Done ? '/nilai' : ($s1Done ? '/berkas' : '/biodata'))) ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $s4Done ? 'bg-white text-slate-700 border-slate-200' : 'bg-slate-100 text-slate-400 border-slate-200' ?> border">
                        5. Cetak Kartu <?= !$s4Done ? '&#128274;' : '' ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <?php if (!empty($data['wajib_pilih_jalur'])): ?>
        <!-- ======================================================================= -->
        <!-- MODAL / HALAMAN WAJIB PILIH JALUR PENDAFTARAN (KETIKA MASIH NULL)        -->
        <!-- ======================================================================= -->
        <div class="fixed inset-0 z-50 flex items-start sm:items-center justify-center bg-slate-900/50 backdrop-blur-sm p-3 sm:p-4 overflow-y-auto">
            <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl sm:rounded-3xl shadow-2xl p-5 sm:p-10 relative my-4 sm:my-8">
                <!-- Header Modal -->
                <div class="text-center max-w-lg mx-auto">
                    <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" class="w-16 h-16 sm:w-20 sm:h-20 object-contain mx-auto mb-3 sm:mb-4 drop-shadow-sm">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Pilih Jalur Pendaftaran</h2>
                    <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-slate-600">
                        Selamat datang, <strong class="text-slate-900"><?= htmlspecialchars($data['user']['nama_lengkap'] ?? '') ?></strong>.
                        Sebelum mengakses menu Dasbor SPMB SMAN 5 Taruna Brawijaya, Anda <span class="text-emerald-700 font-bold">wajib memilih jalur pendaftaran</span> terlebih dahulu.
                    </p>
                </div>

                <?php if (!empty($data['error'])): ?>
                    <div class="mt-5 rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs sm:text-sm text-rose-700 text-center">
                        <?= htmlspecialchars($data['error']) ?>
                    </div>
                <?php endif; ?>

                <!-- Form Pilihan Jalur -->
                <form action="/dashboard/simpanJalur" method="POST" class="mt-6 sm:mt-8 space-y-5 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                        <!-- Opsi 1: Jalur Umum -->
                        <label class="relative flex flex-col p-4 sm:p-6 rounded-2xl border-2 border-slate-200 bg-slate-50/60 hover:bg-emerald-50/40 hover:border-emerald-500 cursor-pointer transition group has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="radio"
                                    name="jalur_pendaftaran"
                                    value="Jalur Umum"
                                    required
                                    class="h-5 w-5 text-emerald-600 border-slate-300 focus:ring-emerald-600"
                                >
                            </div>
                            <span class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition">Jalur Umum</span>
                            <span class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                                Pendaftaran reguler terbuka bagi seluruh calon taruna yang memenuhi syarat akademik, kesehatan, dan kesamaptaan.
                            </span>
                        </label>

                        <!-- Opsi 2: Jalur Beasiswa -->
                        <label class="relative flex flex-col p-4 sm:p-6 rounded-2xl border-2 border-slate-200 bg-slate-50/60 hover:bg-emerald-50/40 hover:border-emerald-500 cursor-pointer transition group has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-teal-100 border border-teal-200 flex items-center justify-center text-teal-700">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>
                                <input
                                    type="radio"
                                    name="jalur_pendaftaran"
                                    value="Jalur Beasiswa"
                                    required
                                    class="h-5 w-5 text-emerald-600 border-slate-300 focus:ring-emerald-600"
                                >
                            </div>
                            <span class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition">Jalur Beasiswa</span>
                            <span class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                                Pendaftaran khusus bagi calon taruna berprestasi unggul atau afirmasi dengan dukungan pembiayaan pendidikan.
                            </span>
                        </label>
                    </div>

                    <div class="rounded-xl bg-emerald-50/70 border border-emerald-200 p-3 text-xs text-emerald-800 flex items-start sm:items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pastikan pilihan jalur sudah sesuai karena dokumen persyaratan akan disesuaikan dengan jalur yang Anda pilih.</span>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 rounded-xl shadow-md shadow-emerald-600/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
                    >
                        Simpan Pilihan Jalur &amp; Lanjutkan ke Dasbor
                    </button>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- ======================================================================= -->
        <!-- HALAMAN DASBOR UTAMA DENGAN 5 MENU NAVIGASI (SETELAH JALUR DIPILIH)     -->
        <!-- ======================================================================= -->
        <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">

            <?php if (!empty($data['success'])): ?>
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 sm:p-4 text-xs sm:text-sm text-emerald-800 font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span><?= htmlspecialchars($data['success']) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['error'])): ?>
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 text-xs sm:text-sm text-rose-700 font-medium">
                    <?= htmlspecialchars($data['error']) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Sidebar 5 Menu Navigasi (Tampil di Desktop lg+) -->
                <aside class="hidden lg:block lg:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm sticky top-24">
                        <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                            Menu Pendaftaran
                        </p>
                        <nav class="space-y-1.5">
                            <!-- 1. Biodata -->
                            <a href="/biodata" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition group">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    1. Biodata
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-md font-bold <?= $s1Done ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>">
                                    <?= $s1Done ? 'Selesai' : 'Aktif' ?>
                                </span>
                            </a>

                            <!-- 2. Upload Berkas -->
                            <a href="/berkas" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold <?= $s1Done ? 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' : 'text-slate-400 bg-slate-50/70' ?> transition group">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    2. Upload Berkas
                                </span>
                                <?php if (!$s1Done): ?>
                                    <span class="text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-semibold">Terkunci</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-0.5 rounded-md <?= $s2Done ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?> font-semibold">
                                        <?= $s2Done ? 'Selesai' : ((int)($data['stats']['jumlah_berkas'] ?? 0) . ' file') ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- 3. Nilai Raport -->
                            <a href="/nilai" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold <?= $s2Done ? 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' : 'text-slate-400 bg-slate-50/70' ?> transition group">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    3. Nilai Raport
                                </span>
                                <?php if (!$s2Done): ?>
                                    <span class="text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-semibold">Terkunci</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-0.5 rounded-md <?= $s3Done ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?> font-semibold">
                                        <?= (int)($data['stats']['jumlah_nilai'] ?? 0) ?>/40
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- 4. Cetak Rekap -->
                            <a href="/cetak/rekap" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold <?= $s3Done ? 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' : 'text-slate-400 bg-slate-50/70' ?> transition group">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    4. Cetak Rekap
                                </span>
                                <?php if (!$s3Done): ?>
                                    <span class="text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-semibold">Terkunci</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-0.5 rounded-md <?= $s4Done ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?> font-semibold">
                                        <?= $s4Done ? 'Selesai' : 'Siap Cetak' ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- 5. Cetak Kartu -->
                            <a href="/cetak/kartu" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold <?= $s4Done ? 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' : 'text-slate-400 bg-slate-50/70' ?> transition group">
                                <span class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    5. Cetak Kartu
                                </span>
                                <?php if (!$s4Done): ?>
                                    <span class="text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-semibold">Terkunci</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold">Terbuka</span>
                                <?php endif; ?>
                            </a>
                        </nav>

                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <span class="text-xs font-medium text-emerald-800 block">Jalur Pendaftaran Anda</span>
                            <p class="mt-0.5 text-sm font-extrabold text-emerald-700">
                                <?= htmlspecialchars($data['user']['jalur_pendaftaran']) ?>
                            </p>
                        </div>
                    </div>
                </aside>

                <!-- Konten Utama Dasbor -->
                <section class="lg:col-span-3 space-y-5 sm:space-y-6">
                    <!-- Banner Sambutan & Ringkasan Akun -->
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl p-5 sm:p-6 shadow-md">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 sm:pb-6 border-b border-white/15">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-white/20 text-white backdrop-blur-sm mb-2">
                                    <?= htmlspecialchars($data['user']['jalur_pendaftaran']) ?>
                                </span>
                                <h2 class="text-lg sm:text-2xl font-extrabold text-white leading-snug">
                                    Selamat Datang, <?= htmlspecialchars($data['user']['nama_lengkap'] ?? '') ?>!
                                </h2>
                                <p class="text-xs sm:text-sm text-emerald-50 mt-1">
                                    Silakan pilih menu tahapan pendaftaran di bawah ini untuk melengkapi berkas seleksi SPMB SMAN 5 Taruna Brawijaya.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-4 sm:pt-5 text-xs sm:text-sm">
                            <div class="bg-white/10 rounded-xl p-2.5 sm:p-0 sm:bg-transparent">
                                <span class="text-[11px] sm:text-xs text-emerald-100 block">NIK</span>
                                <span class="font-bold text-white break-all"><?= htmlspecialchars($data['user']['nik'] ?? '-') ?></span>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2.5 sm:p-0 sm:bg-transparent">
                                <span class="text-[11px] sm:text-xs text-emerald-100 block">NISN</span>
                                <span class="font-bold text-white break-all"><?= htmlspecialchars($data['user']['nisn'] ?? '-') ?></span>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2.5 sm:p-0 sm:bg-transparent">
                                <span class="text-[11px] sm:text-xs text-emerald-100 block">No. HP / WhatsApp</span>
                                <span class="font-bold text-white break-all"><?= htmlspecialchars($data['user']['no_hp'] ?? '-') ?></span>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2.5 sm:p-0 sm:bg-transparent">
                                <span class="text-[11px] sm:text-xs text-emerald-100 block">Tahun Lulus</span>
                                <span class="font-bold text-white"><?= htmlspecialchars((string)($data['user']['tahun_lulus'] ?? '-')) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid 5 Menu Navigasi Utama (Wajib Berurutan) -->
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 sm:mb-4">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Tahapan Pendaftaran (Wajib Berurutan 1 s.d. 5)</h3>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full self-start sm:self-auto">
                                Selesaikan tiap tahap untuk membuka tahap berikutnya
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                            <!-- Card 1: Biodata -->
                            <a href="/biodata" class="group bg-white border-2 <?= $s1Done ? 'border-emerald-200 hover:border-emerald-500' : 'border-emerald-500 ring-2 ring-emerald-500/15' ?> hover:shadow-md rounded-2xl p-4 sm:p-5 shadow-sm transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 group-hover:scale-105 transition">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $s1Done ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                                            <?= $s1Done ? 'Tahap 1 Selesai' : 'Tahap 1 &bull; Mulai di Sini' ?>
                                        </span>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-emerald-700 transition">1. Isi Biodata &amp; Pas Foto</h4>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                        Lengkapi data diri, pas foto 3&times;4, alamat domisili, asal sekolah SMP/MTs, serta informasi orang tua/wali.
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span class="<?= $s1Done ? 'text-emerald-600' : 'text-amber-600' ?> font-bold">
                                        <?= $s1Done ? 'Sudah Lengkap &rarr; Edit' : 'Isi Biodata Sekarang' ?>
                                    </span>
                                    <span class="text-slate-400 group-hover:text-emerald-600 transition">&rarr;</span>
                                </div>
                            </a>

                            <!-- Card 2: Upload Berkas -->
                            <a href="/berkas" class="group rounded-2xl p-4 sm:p-5 shadow-sm transition flex flex-col justify-between border-2 <?= !$s1Done ? 'bg-slate-100/80 border-slate-200 opacity-75' : ($s2Done ? 'bg-white border-emerald-200 hover:border-emerald-500 hover:shadow-md' : 'bg-white border-emerald-500 ring-2 ring-emerald-500/15 hover:shadow-md') ?>">
                                <div>
                                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl <?= $s1Done ? 'bg-teal-50 border-teal-200 text-teal-600' : 'bg-slate-200 border-slate-300 text-slate-500' ?> border flex items-center justify-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= !$s1Done ? 'bg-slate-200 text-slate-600' : ($s2Done ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') ?>">
                                            <?= !$s1Done ? '&#128274; Terkunci' : ($s2Done ? 'Tahap 2 Selesai' : 'Tahap 2 &bull; Aktif') ?>
                                        </span>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900">2. Upload Berkas</h4>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                        Unggah 4 berkas pokok (KK, Surat Sehat, Kelakuan Baik, Ijazah/SKL) serta sertifikat prestasi.
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs">
                                    <span class="<?= !$s1Done ? 'text-slate-500' : 'text-emerald-700' ?> font-bold">
                                        <?= !$s1Done ? 'Selesaikan Tahap 1 dahulu' : ((int)($data['stats']['jumlah_berkas'] ?? 0) . ' Berkas Terunggah') ?>
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                </div>
                            </a>

                            <!-- Card 3: Nilai Raport -->
                            <a href="/nilai" class="group rounded-2xl p-4 sm:p-5 shadow-sm transition flex flex-col justify-between border-2 <?= !$s2Done ? 'bg-slate-100/80 border-slate-200 opacity-75' : ($s3Done ? 'bg-white border-emerald-200 hover:border-emerald-500 hover:shadow-md' : 'bg-white border-emerald-500 ring-2 ring-emerald-500/15 hover:shadow-md') ?>">
                                <div>
                                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl <?= $s2Done ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-slate-200 border-slate-300 text-slate-500' ?> border flex items-center justify-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= !$s2Done ? 'bg-slate-200 text-slate-600' : ($s3Done ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') ?>">
                                            <?= !$s2Done ? '&#128274; Terkunci' : ($s3Done ? 'Tahap 3 Selesai' : 'Tahap 3 &bull; Aktif') ?>
                                        </span>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900">3. Input Nilai Raport</h4>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                        Input nilai 10 mata pelajaran rapor SMP/MTs mulai dari Semester 1 hingga Semester 4 (40 nilai).
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs">
                                    <span class="<?= !$s2Done ? 'text-slate-500' : 'text-emerald-700' ?> font-bold">
                                        <?= !$s2Done ? 'Selesaikan Tahap 2 dahulu' : ((int)($data['stats']['jumlah_nilai'] ?? 0) . ' / 40 Data Nilai') ?>
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                </div>
                            </a>

                            <!-- Card 4: Cetak Rekap -->
                            <a href="/cetak/rekap" class="group rounded-2xl p-4 sm:p-5 shadow-sm transition flex flex-col justify-between border-2 <?= !$s3Done ? 'bg-slate-100/80 border-slate-200 opacity-75' : ($s4Done ? 'bg-white border-emerald-200 hover:border-emerald-500 hover:shadow-md' : 'bg-white border-emerald-500 ring-2 ring-emerald-500/15 hover:shadow-md') ?>">
                                <div>
                                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl <?= $s3Done ? 'bg-teal-50 border-teal-200 text-teal-600' : 'bg-slate-200 border-slate-300 text-slate-500' ?> border flex items-center justify-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= !$s3Done ? 'bg-slate-200 text-slate-600' : ($s4Done ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') ?>">
                                            <?= !$s3Done ? '&#128274; Terkunci' : ($s4Done ? 'Tahap 4 Selesai' : 'Tahap 4 &bull; Aktif') ?>
                                        </span>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900">4. Cetak Rekap Nilai</h4>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                        Buka dan cetak bukti rekapitulasi nilai rapor beserta surat pernyataan resmi untuk membuka Cetak Kartu.
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs">
                                    <span class="<?= !$s3Done ? 'text-slate-500' : 'text-emerald-700' ?> font-bold">
                                        <?= !$s3Done ? 'Selesaikan Tahap 3 dahulu' : ($s4Done ? 'Sudah Dibuka &bull; Cetak PDF' : 'Buka &amp; Cetak Rekap') ?>
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                </div>
                            </a>

                            <!-- Card 5: Cetak Kartu -->
                            <a href="/cetak/kartu" class="group rounded-2xl p-4 sm:p-5 shadow-sm transition flex flex-col justify-between border-2 <?= !$s4Done ? 'bg-slate-100/80 border-slate-200 opacity-75' : 'bg-white border-emerald-500 ring-2 ring-emerald-500/15 hover:shadow-md' ?>">
                                <div>
                                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl <?= $s4Done ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-slate-200 border-slate-300 text-slate-500' ?> border flex items-center justify-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= !$s4Done ? 'bg-slate-200 text-slate-600' : 'bg-emerald-100 text-emerald-800' ?>">
                                            <?= !$s4Done ? '&#128274; Terkunci' : 'Tahap 5 &bull; Siap Cetak' ?>
                                        </span>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900">5. Cetak Kartu Peserta</h4>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                        Cetak Kartu Peserta Seleksi SPMB SMAN 5 Taruna Brawijaya beserta checklist berkas fisik.
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs">
                                    <span class="<?= !$s4Done ? 'text-slate-500' : 'text-emerald-700' ?> font-bold">
                                        <?= !$s4Done ? 'Selesaikan Tahap 4 dahulu' : 'Cetak Kartu Peserta' ?>
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>

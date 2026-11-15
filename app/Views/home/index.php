<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <?php
    $data['meta_description'] = 'Portal Resmi Seleksi Penerimaan Murid Baru (SPMB) SMAN 5 Taruna Brawijaya Jawa Timur Tahun Ajaran 2027/2028. Kuota 270 Taruna/Taruni, Jalur Umum & Jalur Beasiswa Prestasi, Pendaftaran Online 16 November s.d. 26 Desember 2026.';
    $data['meta_robots']      = 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
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
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col overflow-x-hidden">

    <?php
    $isLoggedIn   = !empty($data['is_logged_in']);
    $userNama     = $data['user_nama'] ?? '';
    $userRole     = $data['user_role'] ?? 'calon_taruna';
    $dashboardUrl = $data['dashboard_url'] ?? '/dashboard';
    ?>

    <!-- Navbar Utama Sticky & Responsif -->
    <header class="bg-white/95 border-b border-slate-200 sticky top-0 z-40 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-3">
            <!-- Brand Identitas Sekolah (Logo Navbar pentab.png) -->
            <a href="/" class="flex items-center gap-3 min-w-0">
                <img src="/assets/img/pentab.png" alt="Logo SPMB SMAN 5 Taruna Brawijaya" class="h-10 sm:h-12 w-auto object-contain shrink-0">
                <div class="hidden sm:block min-w-0 border-l border-slate-200 pl-3">
                    <span class="block text-xs sm:text-sm font-extrabold text-slate-900 truncate">SMAN 5 Taruna Brawijaya</span>
                    <span class="block text-[11px] text-emerald-700 font-semibold truncate">Jawa Timur &bull; SPMB 2027/2028</span>
                </div>
            </a>

            <!-- Navigasi Menu Desktop -->
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="#syarat" class="hover:text-emerald-700 transition">Persyaratan</a>
                <a href="#jalur" class="hover:text-emerald-700 transition">Jalur Seleksi</a>
                <a href="#jadwal" class="hover:text-emerald-700 transition">Jadwal SPMB</a>
                <a href="#alur" class="hover:text-emerald-700 transition">Alur &amp; Berkas</a>
                <a href="#faq" class="hover:text-emerald-700 transition">FAQ</a>
            </nav>

            <!-- Tombol CTA Masuk / Daftar / Dasbor -->
            <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                        <span><?= $userRole === 'admin' ? 'Panel Admin' : 'Dasbor Saya' ?></span>
                        <span>&rarr;</span>
                    </a>
                <?php else: ?>
                    <a href="/auth/login" class="whitespace-nowrap px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition">
                        Masuk
                    </a>
                    <a href="/auth/register" class="whitespace-nowrap px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                        Daftar Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bar Navigasi Cepat Mobile (< lg) -->
        <div class="lg:hidden border-t border-slate-100 bg-slate-50/90 px-4 py-2 overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-2 min-w-max text-xs font-semibold text-slate-600">
                <a href="#syarat" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700">Persyaratan</a>
                <a href="#jalur" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700">Jalur Seleksi</a>
                <a href="#jadwal" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700">Jadwal SPMB</a>
                <a href="#alur" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700">Alur &amp; Berkas</a>
                <a href="#faq" class="px-3 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 hover:text-emerald-700">Tanya Jawab</a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-b from-emerald-50/80 via-white to-slate-50 pt-8 pb-12 sm:py-16 lg:py-20 border-b border-slate-200/80">
        <!-- Dekorasi Latar -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-24 -right-24 w-80 sm:w-[420px] h-80 sm:h-[420px] rounded-full bg-emerald-400/15 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-80 sm:w-[420px] h-80 sm:h-[420px] rounded-full bg-teal-400/15 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">

                <!-- Kolom Kiri: Headline & Tombol Aksi -->
                <div class="lg:col-span-7 space-y-5 text-center lg:text-left">
                    <div class="inline-flex flex-wrap items-center justify-center lg:justify-start gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/90 border border-emerald-200 text-emerald-800 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span>Seleksi Penerimaan Murid Baru (SPMB) 2027/2028</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-900 font-extrabold text-[11px]">Kuota 270 Taruna/Taruni</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Terwujudnya <span class="bg-gradient-to-r from-emerald-600 to-teal-700 bg-clip-text text-transparent">Kader Pemimpin Bangsa</span> yang Berakhlak Mulia, Cerdas, Unggul, &amp; Berintegritas
                    </h1>

                    <p class="text-sm sm:text-base lg:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Selamat datang di Portal Resmi <strong>SPMB SMAN 5 Taruna Brawijaya Jawa Timur</strong> (Jl. Selomangleng No. 2 Kota Kediri). Sistem pendaftaran dilakukan secara <strong>online penuh</strong> dan dapat diakses kapan pun di mana pun.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 pt-1">
                        <?php if ($isLoggedIn): ?>
                            <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/25 hover:from-emerald-700 hover:to-teal-700 transition">
                                <span>Lanjutkan ke <?= $userRole === 'admin' ? 'Panel Admin' : 'Dasbor Pendaftaran' ?></span>
                                <span>&rarr;</span>
                            </a>
                        <?php else: ?>
                            <a href="/auth/register" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-sm font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/25 hover:from-emerald-700 hover:to-teal-700 transition">
                                <span>Daftar Akun Calon Taruna</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="/auth/login" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl text-sm font-bold bg-white text-slate-800 border border-slate-300 hover:border-emerald-300 hover:bg-emerald-50/50 shadow-sm transition">
                                <span>Sudah Punya Akun? Masuk</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Highlight Motto & Info Singkat -->
                    <div class="pt-3 space-y-3">
                        <div class="inline-block px-4 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-left">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-amber-900">Widya Widigda Wiguna Yasa</p>
                            <p class="text-[11px] font-medium text-amber-800">Pendidikan, yang Mencerdaskan, Bermanfaat, dan Mulia</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 max-w-xl mx-auto lg:mx-0 text-left">
                            <div class="flex items-center gap-2 bg-white/80 border border-slate-200/80 rounded-xl px-3 py-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-extrabold shrink-0">270</span>
                                <span class="text-xs font-semibold text-slate-700">Kuota Taruna/Taruni</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/80 border border-slate-200/80 rounded-xl px-3 py-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-extrabold shrink-0">&#10003;</span>
                                <span class="text-xs font-semibold text-slate-700">Umum &amp; Beasiswa</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/80 border border-slate-200/80 rounded-xl px-3 py-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-extrabold shrink-0">&#10003;</span>
                                <span class="text-xs font-semibold text-slate-700">16 Nov &ndash; 26 Des 2026</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Foto Utama Kepala Sekolah & Taruna Berprestasi -->
                <div class="lg:col-span-5 flex items-center justify-center">
                    <div class="relative w-full max-w-md lg:max-w-full mx-auto">
                        <img
                            src="/assets/img/Smata-Jaya-Foto-Utama-2.png"
                            alt="Kepala SMAN 5 Taruna Brawijaya Jawa Timur beserta Taruna-Taruni Berprestasi"
                            fetchpriority="high"
                            decoding="async"
                            class="w-full h-auto object-contain drop-shadow-xl mx-auto"
                        >
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 1: PERSYARATAN SPMB & KUOTA PENERIMAAN (#syarat) -->
    <section id="syarat" class="py-12 sm:py-16 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Kualifikasi Calon Peserta</span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">Persyaratan SPMB 2027/2028</h2>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                        Pastikan calon Taruna/Taruni memenuhi 5 persyaratan utama Seleksi Penerimaan Murid Baru SMAN 5 Taruna Brawijaya Jawa Timur berikut.
                    </p>
                </div>

                <div class="shrink-0 bg-gradient-to-r from-emerald-700 to-teal-700 text-white rounded-2xl px-5 py-3.5 shadow-md flex items-center gap-3.5">
                    <div class="text-center px-3 py-1 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-xl sm:text-2xl">
                        270
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-100 block">Kuota Penerimaan</span>
                        <span class="text-sm sm:text-base font-extrabold">Taruna / Taruni Baru</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Syarat 1 -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-3 hover:border-emerald-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base flex items-center justify-center shadow-sm">1</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Kewarganegaraan &amp; Usia</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <strong>Warga Negara Indonesia</strong> usia <strong>14 s.d. 17 Tahun</strong> saat awal Tahun Ajaran <strong>2027/2028</strong>.
                    </p>
                </div>

                <!-- Syarat 2 -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-3 hover:border-emerald-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base flex items-center justify-center shadow-sm">2</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Status Pendidikan Asal</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <strong>Murid SMP/MTs/Sederajat Kelas IX</strong> Tahun Ajaran <strong>2026/2027</strong> atau <strong>Murid Lulusan Tahun Sebelumnya</strong>.
                    </p>
                </div>

                <!-- Syarat 3 -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-3 hover:border-emerald-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base flex items-center justify-center shadow-sm">3</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Tinggi &amp; Berat Badan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Tinggi badan minimal <strong>155 cm untuk Laki-Laki</strong> dan minimal <strong>150 cm untuk Perempuan</strong> dengan berat badan proporsional.
                    </p>
                </div>

                <!-- Syarat 4 -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-3 hover:border-emerald-400 transition">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base flex items-center justify-center shadow-sm">4</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Nilai Rapor Akademik</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <strong>Nilai Rata-rata Rapor Semester 1, 2, 3, dan 4 minimal 80</strong>.
                    </p>
                </div>

                <!-- Syarat 5 -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 space-y-3 hover:border-emerald-400 transition md:col-span-2 lg:col-span-1">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 font-extrabold text-base flex items-center justify-center shadow-sm">5</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Kondisi Kesehatan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <strong>Sehat Jasmani dan Rohani</strong> (dibuktikan dengan Surat Keterangan Sehat resmi &amp; tes kesehatan).
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: JALUR PENDAFTARAN (#jalur) -->
    <section id="jalur" class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Pilihan Jalur Masuk</span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">Jalur Pendaftaran SPMB</h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Pilih jalur seleksi yang sesuai dengan kualifikasi akademik maupun prestasi Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 1. Jalur Umum -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 flex flex-col justify-between space-y-6 hover:border-emerald-400 transition shadow-sm">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-slate-900">
                                <span class="px-1.5 py-0.2 rounded bg-rose-600 text-white">1</span>
                                <span>JALUR UMUM</span>
                            </span>
                            <span class="text-xs font-bold text-slate-500">Seleksi Reguler</span>
                        </div>
                        <h3 class="text-lg sm:text-2xl font-extrabold text-slate-900">Jalur Seleksi Reguler Calon Taruna</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Jalur seleksi reguler bagi peserta yang ingin menjadi Taruna di <strong>SMAN 5 Taruna Brawijaya Jawa Timur</strong>.
                        </p>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-700 pt-1">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Memenuhi 5 persyaratan umum SPMB (nilai rata-rata rapor Semester 1, 2, 3, dan 4 minimal 80).</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Mengunggah berkas administrasi online dan mengikuti tahapan seleksi akademik, kesehatan, kesamaptaan, serta Pantukhir.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Dapat melampirkan sertifikat prestasi (opsional, maksimal 5 sertifikat) sebagai nilai tambah.</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/auth/register" class="w-full text-center py-3 px-4 rounded-xl text-xs sm:text-sm font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Daftar Jalur Umum &rarr;
                    </a>
                </div>

                <!-- 2. Jalur Beasiswa Prestasi -->
                <div class="rounded-3xl border-2 border-emerald-500/80 bg-gradient-to-b from-emerald-50/60 to-white p-6 sm:p-8 flex flex-col justify-between space-y-6 shadow-md">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-slate-900">
                                <span class="px-1.5 py-0.2 rounded bg-rose-600 text-white">2</span>
                                <span>JALUR BEASISWA PRESTASI</span>
                            </span>
                            <span class="text-xs font-bold text-emerald-700">Prestasi Akademik / Nonakademik</span>
                        </div>
                        <h3 class="text-lg sm:text-2xl font-extrabold text-slate-900">Jalur Beasiswa Prestasi</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Jalur seleksi bagi peserta yang memiliki <strong>prestasi akademik maupun nonakademik</strong> tingkat <strong>Kota/Kabupaten, Provinsi, Nasional, dan Internasional</strong>.
                        </p>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-700 pt-1">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Memenuhi 5 persyaratan umum SPMB (nilai rata-rata rapor Semester 1, 2, 3, dan 4 minimal 80).</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Melampirkan bukti <strong>Piagam / Sertifikat Prestasi (Maksimal 5 Sertifikat)</strong> tingkat Kota/Kab, Provinsi, Nasional, atau Internasional.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-extrabold">&check;</span>
                                <span>Terbuka untuk prestasi kejuaraan bidang Akademik (OSN, LKTI, Olimpiade) maupun Nonakademik (O2SN, FLS3N, Olahraga, Seni, Baris-Berbaris, dll).</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/auth/register" class="w-full text-center py-3 px-4 rounded-xl text-xs sm:text-sm font-bold bg-teal-700 text-white hover:bg-teal-800 shadow-sm transition">
                        Daftar Jalur Beasiswa Prestasi &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: JADWAL RESMI SPMB (#jadwal) -->
    <section id="jadwal" class="py-12 sm:py-16 bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 text-white border-b border-emerald-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300">Timeline Resmi Seleksi</span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-white">Jadwal SPMB 2027/2028</h2>
                <p class="text-xs sm:text-sm text-emerald-100">
                    Catat dan perhatikan setiap tanggal tahapan Seleksi Penerimaan Murid Baru SMAN 5 Taruna Brawijaya Jawa Timur.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                <!-- Tahap 1 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            1. Pendaftaran Online / Seleksi Tahap I
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        16 November s.d. 26 Desember 2026
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Registrasi akun daring, pengisian biodata, unggah berkas persyaratan, dan input nilai rapor Semester 1–4.
                    </p>
                </div>

                <!-- Tahap 2 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            2. Pengumuman Tahap I
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        29 Desember 2026
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Pengumuman hasil seleksi administrasi Tahap I dan daftar peserta yang berhak mengikuti Seleksi Tahap II.
                    </p>
                </div>

                <!-- Tahap 3 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            3. Seleksi Tahap II
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        4 &ndash; 10 Januari 2027
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Pelaksanaan rangkaian tes akademik, psikotes, pemeriksaan kesehatan, dan kesamaptaan jasmani.
                    </p>
                </div>

                <!-- Tahap 4 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            4. Pantukhir
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        11 Januari 2027
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Sidang Pemantauan Terakhir (Pantukhir) calon Taruna/Taruni SMAN 5 Taruna Brawijaya Jawa Timur.
                    </p>
                </div>

                <!-- Tahap 5 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            5. Pengumuman Akhir
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        19 Januari 2027
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Pengumuman kelulusan akhir 270 Calon Taruna/Taruni Baru Tahun Ajaran 2027/2028.
                    </p>
                </div>

                <!-- Tahap 6 -->
                <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 space-y-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-lg bg-amber-400 text-slate-900 text-xs font-extrabold">
                            6. Daftar Ulang
                        </span>
                    </div>
                    <p class="text-lg sm:text-xl font-extrabold text-white pt-1">
                        19 &ndash; 22 Januari 2027
                    </p>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Pemberkasan dan registrasi daftar ulang bagi peserta yang dinyatakan lulus seleksi akhir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: CARA PENDAFTARAN ONLINE & BERKAS YANG DISIAPKAN (#alur & #berkas) -->
    <section id="alur" class="py-12 sm:py-16 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center max-w-3xl mx-auto space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Cara Pendaftaran Online</span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">Alur Pendaftaran &amp; Berkas yang Disiapkan</h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    Sistem pendaftaran dilakukan secara online penuh melalui portal Seleksi Penerimaan Murid Baru (SPMB) dan bisa diakses kapan pun di mana pun.
                </p>
            </div>

            <!-- 5 Langkah Alur Pendaftaran Online -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center">1</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Buat Akun SPMB</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Registrasi menggunakan NIK (16 digit), NISN (10 digit), Nama Lengkap, Email, dan Nomor WhatsApp aktif.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center">2</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pilih Jalur &amp; Biodata</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pilih Jalur Umum atau Jalur Beasiswa Prestasi, lengkapi biodata diri, sekolah asal, orang tua, dan Pas Foto 3x4.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center">3</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Unggah Berkas Online</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Unggah scan/foto KK, Surat Sehat, SKKB, Ijazah/SKL, serta sertifikat prestasi (Format JPG/PNG maks. 5 MB).
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold text-sm flex items-center justify-center">4</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Input Nilai Rapor</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Masukkan nilai 10 mata pelajaran untuk Semester 1, 2, 3, dan 4 (rata-rata minimal 80).
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 shadow-sm space-y-2.5 sm:col-span-2 lg:col-span-1">
                    <div class="w-9 h-9 rounded-xl bg-teal-700 text-white font-extrabold text-sm flex items-center justify-center">5</div>
                    <h3 class="text-sm font-extrabold text-slate-900">Cetak Kartu &amp; Rekap</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Cetak Kartu Tanda Peserta dan Lembar Rekapitulasi Nilai Rapor resmi sebagai bukti seleksi.
                    </p>
                </div>
            </div>

            <!-- Berkas yang Disiapkan untuk Pendaftaran Online -->
            <div id="berkas" class="pt-4 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-t border-slate-200 pt-8">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Kelengkapan Administrasi</span>
                        <h3 class="text-lg sm:text-2xl font-extrabold text-slate-900">Berkas yang Disiapkan untuk Pendaftaran Online</h3>
                    </div>
                    <span class="inline-block self-start md:self-auto px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Format File: JPG / JPEG / PNG (Maks. 5 MB per file)
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                        <span class="text-xs font-bold text-emerald-700">1. Identitas Diri</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Pas Foto Resmi 3x4</h4>
                        <p class="text-xs text-slate-500">Diunggah pada menu Biodata Diri. Gunakan pakaian seragam sekolah asal yang rapi.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                        <span class="text-xs font-bold text-emerald-700">2. Dokumen Kependudukan</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Kartu Keluarga (KK) Asli</h4>
                        <p class="text-xs text-slate-500">Pastikan NIK calon taruna (usia 14–17 tahun pada awal TA 2027/2028) terbaca jelas.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                        <span class="text-xs font-bold text-emerald-700">3. Dokumen Medis</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Surat Keterangan Sehat</h4>
                        <p class="text-xs text-slate-500">Bukti sehat jasmani serta keterangan tinggi &amp; berat badan (Min. 155 cm Putra / 150 cm Putri).</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                        <span class="text-xs font-bold text-emerald-700">4. Dokumen Karakter</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Surat Keterangan Kelakuan Baik</h4>
                        <p class="text-xs text-slate-500">Surat keterangan berkelakuan baik dari SMP/MTs asal atau SKCK dari Kepolisian.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                        <span class="text-xs font-bold text-emerald-700">5. Dokumen Pendidikan</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Ijazah / SKL / Kartu Pelajar</h4>
                        <p class="text-xs text-slate-500">Bukti murid SMP/MTs/Sederajat Kelas IX TA 2026/2027 atau lulusan tahun sebelumnya.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-1.5">
                        <span class="text-xs font-bold text-teal-700">6. Dokumen Prestasi (Maks. 5 File)</span>
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900">Piagam / Sertifikat Kejuaraan</h4>
                        <p class="text-xs text-slate-600">Tingkat Kota/Kab, Provinsi, Nasional, atau Internasional (Akademik &amp; Nonakademik).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: TANYA JAWAB / FAQ (#faq) -->
    <section id="faq" class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-600">Pusat Bantuan</span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            </div>

            <div class="space-y-3">
                <details class="group bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm" open>
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm sm:text-base text-slate-900 list-none">
                        <span>Apa saja syarat utama pendaftaran SPMB SMAN 5 Taruna Brawijaya 2027/2028?</span>
                        <span class="text-emerald-600 font-extrabold group-open:rotate-180 transition-transform">&#9662;</span>
                    </summary>
                    <p class="mt-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Warga Negara Indonesia usia <strong>14 s.d. 17 tahun</strong> saat awal Tahun Ajaran 2027/2028, murid SMP/MTs/Sederajat Kelas IX TA 2026/2027 atau lulusan tahun sebelumnya, tinggi badan minimal <strong>155 cm (Laki-Laki)</strong> dan <strong>150 cm (Perempuan)</strong> dengan berat badan proporsional, nilai rata-rata rapor Semester 1, 2, 3, dan 4 <strong>minimal 80</strong>, serta sehat jasmani dan rohani.
                    </p>
                </details>

                <details class="group bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm sm:text-base text-slate-900 list-none">
                        <span>Kapan jadwal Pendaftaran Online / Seleksi Tahap I dibuka?</span>
                        <span class="text-emerald-600 font-extrabold group-open:rotate-180 transition-transform">&#9662;</span>
                    </summary>
                    <p class="mt-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pendaftaran Online / Seleksi Tahap I dilaksanakan pada <strong>16 November s.d. 26 Desember 2026</strong>, dilanjutkan Pengumuman Tahap I (<strong>29 Desember 2026</strong>), Seleksi Tahap II (<strong>4–10 Januari 2027</strong>), Pantukhir (<strong>11 Januari 2027</strong>), Pengumuman Akhir (<strong>19 Januari 2027</strong>), dan Daftar Ulang (<strong>19–22 Januari 2027</strong>).
                    </p>
                </details>

                <details class="group bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-sm sm:text-base text-slate-900 list-none">
                        <span>Ke mana saya dapat menghubungi panitia jika membutuhkan informasi lebih lanjut?</span>
                        <span class="text-emerald-600 font-extrabold group-open:rotate-180 transition-transform">&#9662;</span>
                    </summary>
                    <p class="mt-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Anda dapat mengakses portal resmi <strong>spmb.smatarunakediri.sch.id</strong> atau menghubungi layanan WhatsApp Panitia SPMB di nomor <strong><a href="https://wa.me/628157788995" target="_blank" class="text-emerald-700 underline">0815-7788-995</a></strong>.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- FOOTER RESMI -->
    <footer class="bg-slate-900 text-slate-300 py-10 sm:py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-slate-800">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" loading="lazy" decoding="async" class="h-11 w-11 object-contain shrink-0">
                        <div>
                            <p class="font-extrabold text-white text-sm sm:text-base">SMAN 5 Taruna Brawijaya</p>
                            <p class="text-xs text-emerald-400">Jawa Timur &bull; Widya Widigda Wiguna Yasa</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Jl. Selomangleng No. 2, Kota Kediri, Jawa Timur 64114.
                    </p>
                    <div class="pt-1 flex flex-wrap gap-2 text-[11px] text-slate-400">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700">IG/Threads: @sma.taruna.brawijaya</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700">FB/YT: SMAN 5 Taruna Brawijaya</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700">TikTok: Smata Jaya</span>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <p class="text-xs font-bold uppercase tracking-wider text-white">Tautan Cepat Portal</p>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li><a href="/auth/register" class="hover:text-emerald-400 transition">Registrasi Calon Taruna Baru (Kuota 270)</a></li>
                        <li><a href="/auth/login" class="hover:text-emerald-400 transition">Login Peserta &amp; Panitia</a></li>
                        <li><a href="#syarat" class="hover:text-emerald-400 transition">5 Persyaratan Utama SPMB 2027/2028</a></li>
                        <li><a href="#jadwal" class="hover:text-emerald-400 transition">Jadwal Seleksi Tahap I, II &amp; Pantukhir</a></li>
                        <li><a href="/sitemap.xml" target="_blank" class="hover:text-emerald-400 transition">Peta Situs (XML Sitemap)</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-white">Informasi Lebih Lanjut</p>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Portal Resmi Pendaftaran:<br>
                        <strong class="text-amber-400">spmb.smatarunakediri.sch.id</strong>
                    </p>
                    <a
                        href="https://wa.me/628157788995"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold shadow-sm transition"
                    >
                        <span>WhatsApp Panitia: 0815-7788-995</span>
                    </a>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
                <p>&copy; <?= date('Y') ?> Panitia SPMB SMAN 5 Taruna Brawijaya Jawa Timur (TA 2027/2028).</p>
                <p>Pendidikan, yang Mencerdaskan, Bermanfaat, dan Mulia</p>
            </div>
        </div>
    </footer>

</body>
</html>

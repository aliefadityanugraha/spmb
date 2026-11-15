<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Panel Administrasi & Verifikasi Panitia Seleksi SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
    $data['meta_robots']      = 'noindex, nofollow';
    require __DIR__ . '/../partials/seo_head.php';
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @media print {
            @page { size: A4 landscape; margin: 10mm 12mm; }
            body { background: #ffffff !important; color: #000000 !important; }
            .no-print { display: none !important; }
            .print-full { width: 100% !important; max-width: 100% !important; box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col overflow-x-hidden">

    <?php
    $activeMenu = $data['active_menu'] ?? 'ringkasan';
    $adminName  = $data['admin']['nama_lengkap'] ?? 'Administrator';

    $menus = [
        'ringkasan' => ['label' => '1. Ringkasan Dasbor', 'short' => 'Ringkasan', 'url' => '/admin'],
        'pendaftar' => ['label' => '2. Data Calon Taruna', 'short' => 'Data Pendaftar', 'url' => '/admin/pendaftar'],
        'berkas'    => ['label' => '3. Verifikasi Berkas', 'short' => 'Verifikasi Berkas', 'url' => '/admin/berkas'],
        'nilai'     => ['label' => '4. Peringkat Nilai Rapor', 'short' => 'Peringkat Nilai', 'url' => '/admin/nilai'],
        'laporan'   => ['label' => '5. Laporan & Ekspor Data', 'short' => 'Laporan & CSV', 'url' => '/admin/laporan']
    ];
    ?>

    <!-- Topbar Admin Responsif -->
    <header class="no-print bg-white/95 border-b border-slate-200 sticky top-0 z-30 backdrop-blur shadow-sm">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-14 sm:h-16 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <a href="/admin" class="shrink-0">
                    <img src="/assets/img/pentab.png" alt="SPMB SMAN 5 Taruna Brawijaya" class="h-9 sm:h-11 w-auto object-contain">
                </a>
                <div class="hidden sm:block min-w-0 border-l border-slate-200 pl-3">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-tight truncate">Panel Admin SPMB</h1>
                        <span class="whitespace-nowrap px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Panitia Seleksi
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 truncate">SMAN 5 Taruna Brawijaya Jawa Timur</p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-bold text-slate-800 truncate max-w-[160px]"><?= htmlspecialchars($adminName) ?></p>
                    <p class="text-[11px] text-emerald-700 font-semibold">Administrator</p>
                </div>

                <a href="/admin/exportCsv" class="hidden md:inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                    Unduh CSV
                </a>

                <a href="/auth/logout" class="whitespace-nowrap px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition">
                    Keluar
                </a>
            </div>
        </div>

        <!-- Bar Navigasi Horizontal Khusus Mobile / Tablet (< lg) -->
        <div class="lg:hidden border-t border-slate-100 bg-slate-50/90 px-3 py-2 overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-1.5 min-w-max">
                <?php foreach ($menus as $key => $m): ?>
                    <a
                        href="<?= $m['url'] ?>"
                        class="whitespace-nowrap px-3 py-1.5 rounded-lg text-xs <?= $activeMenu === $key ? 'font-bold bg-emerald-600 text-white shadow-sm' : 'font-semibold bg-white text-slate-700 border border-slate-200' ?>"
                    >
                        <?= htmlspecialchars($m['short']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <!-- Konten Utama dengan Sidebar Desktop -->
    <div class="flex-1 max-w-[1400px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Sidebar Menu Admin (Desktop lg+) -->
            <aside class="no-print hidden lg:block lg:col-span-3 min-w-0">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm sticky top-24">
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                        Menu Administrator
                    </p>
                    <nav class="space-y-1.5">
                        <?php foreach ($menus as $key => $m): ?>
                            <a
                                href="<?= $m['url'] ?>"
                                class="flex items-center justify-between gap-2 px-3.5 py-2.5 rounded-xl text-sm transition <?= $activeMenu === $key ? 'font-bold bg-emerald-50 text-emerald-700 border border-emerald-200' : 'font-semibold text-slate-700 hover:bg-emerald-50/60 hover:text-emerald-700' ?>"
                            >
                                <span class="truncate"><?= htmlspecialchars($m['label']) ?></span>
                                <?php if ($activeMenu === $key): ?>
                                    <span class="w-2 h-2 rounded-full bg-emerald-600 shrink-0"></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a href="/admin/exportCsv" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                            Ekspor Semua Data (CSV)
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Kolom Konten Dinamis Admin (min-w-0 mencegah tabel merusak grid) -->
            <section class="print-full min-w-0 lg:col-span-9 space-y-5 sm:space-y-6">
                <?php if (!empty($data['success'])): ?>
                    <div class="no-print rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 sm:p-4 text-xs sm:text-sm text-emerald-800 font-medium flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="break-words"><?= htmlspecialchars($data['success']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['error'])): ?>
                    <div class="no-print rounded-xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 text-xs sm:text-sm text-rose-700 font-medium break-words">
                        <?= htmlspecialchars($data['error']) ?>
                    </div>
                <?php endif; ?>

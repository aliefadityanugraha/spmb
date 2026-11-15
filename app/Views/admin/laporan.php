<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$pendaftar   = $data['pendaftar'] ?? [];
$stats       = $data['stats'] ?? [];
$filterJalur = $data['filter_jalur'] ?? '';
?>

<!-- Toolbar Aksi Laporan (Disembunyikan saat mode cetak kertas) -->
<div class="no-print bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="min-w-0">
        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Laporan Rekapitulasi &amp; Ekspor Data</h2>
        <p class="text-xs sm:text-sm text-slate-500">
            Cetak laporan rekapitulasi pendaftar ke kertas A4 Landscape atau unduh dalam format CSV / Microsoft Excel.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
        <a href="/admin/exportCsv?jalur=<?= urlencode($filterJalur) ?>" class="whitespace-nowrap px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
            Unduh CSV / Excel
        </a>
        <button
            type="button"
            onclick="window.print()"
            class="whitespace-nowrap px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition"
        >
            Cetak Laporan (PDF / Kertas)
        </button>
    </div>
</div>

<!-- Lembar Laporan Resmi -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-8 shadow-sm space-y-5 overflow-hidden">
    <div class="border-b-2 border-black pb-4 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 text-center">
        <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" class="w-14 h-14 object-contain shrink-0">
        <div>
            <p class="text-[11px] sm:text-xs font-bold uppercase tracking-widest">Pemerintah Provinsi Jawa Timur &mdash; Dinas Pendidikan</p>
            <h3 class="text-sm sm:text-xl font-extrabold uppercase mt-0.5">
                Laporan Rekapitulasi Pendaftar SPMB SMAN 5 Taruna Brawijaya
            </h3>
            <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-xs text-slate-600 mt-1">
                <span>Total Pendaftar: <strong><?= (int)($stats['total_pendaftar'] ?? 0) ?></strong></span>
                <span>&bull;</span>
                <span>Jalur Umum: <strong><?= (int)($stats['total_umum'] ?? 0) ?></strong></span>
                <span>&bull;</span>
                <span>Jalur Beasiswa: <strong><?= (int)($stats['total_beasiswa'] ?? 0) ?></strong></span>
                <span>&bull;</span>
                <span>Dicetak: <?= date('d/m/Y H:i') ?> WIB</span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] border-collapse border border-slate-300 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-700">
                    <th class="border border-slate-300 py-2 px-2 text-center w-10">No</th>
                    <th class="border border-slate-300 py-2 px-3 text-left whitespace-nowrap">NISN / NIK</th>
                    <th class="border border-slate-300 py-2 px-3 text-left">Nama Lengkap</th>
                    <th class="border border-slate-300 py-2 px-2 text-center whitespace-nowrap">L/P</th>
                    <th class="border border-slate-300 py-2 px-3 text-left">Asal Sekolah</th>
                    <th class="border border-slate-300 py-2 px-3 text-left whitespace-nowrap">Jalur Pendaftaran</th>
                    <th class="border border-slate-300 py-2 px-3 text-left whitespace-nowrap">No. HP</th>
                    <th class="border border-slate-300 py-2 px-2 text-center whitespace-nowrap">Berkas Valid</th>
                    <th class="border border-slate-300 py-2 px-2 text-center whitespace-nowrap">Rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pendaftar)): ?>
                    <tr>
                        <td colspan="9" class="border border-slate-300 py-6 text-center text-slate-500">Belum ada data pendaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pendaftar as $idx => $p): ?>
                        <tr>
                            <td class="border border-slate-300 py-1.5 px-2 text-center align-middle"><?= $idx + 1 ?></td>
                            <td class="border border-slate-300 py-1.5 px-3 align-middle whitespace-nowrap">
                                <span class="font-semibold"><?= htmlspecialchars($p['nisn']) ?></span><br>
                                <span class="text-[10px] text-slate-500"><?= htmlspecialchars($p['nik']) ?></span>
                            </td>
                            <td class="border border-slate-300 py-1.5 px-3 font-bold align-middle"><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                            <td class="border border-slate-300 py-1.5 px-2 text-center align-middle whitespace-nowrap"><?= htmlspecialchars($p['jenis_kelamin'] ?? '-') ?></td>
                            <td class="border border-slate-300 py-1.5 px-3 align-middle"><?= htmlspecialchars($p['nama_sekolah_asal'] ?? '-') ?></td>
                            <td class="border border-slate-300 py-1.5 px-3 align-middle whitespace-nowrap"><?= htmlspecialchars($p['jalur_pendaftaran'] ?? 'Belum Pilih') ?></td>
                            <td class="border border-slate-300 py-1.5 px-3 align-middle whitespace-nowrap"><?= htmlspecialchars($p['no_hp']) ?></td>
                            <td class="border border-slate-300 py-1.5 px-2 text-center align-middle whitespace-nowrap"><?= (int)$p['berkas_valid'] ?> / <?= (int)$p['jumlah_berkas'] ?></td>
                            <td class="border border-slate-300 py-1.5 px-2 text-center font-bold align-middle whitespace-nowrap"><?= $p['rata_nilai'] !== null ? number_format((float)$p['rata_nilai'], 2) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>

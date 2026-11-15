<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$ranking     = $data['ranking'] ?? [];
$filterJalur = $data['filter_jalur'] ?? '';
?>

<!-- Header & Filter Jalur Pemeringkatan -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="min-w-0">
        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Peringkat &amp; Rekap Nilai Rapor (Sem 1–4)</h2>
        <p class="text-xs sm:text-sm text-slate-500">
            Diurutkan otomatis berdasarkan rata-rata nilai rapor tertinggi dari Semester 1 s.d. Semester 4.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2 shrink-0">
        <a href="/admin/nilai" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs font-bold border <?= $filterJalur === '' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' ?>">
            Semua Jalur
        </a>
        <a href="/admin/nilai?jalur=<?= urlencode('Jalur Umum') ?>" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs font-bold border <?= $filterJalur === 'Jalur Umum' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' ?>">
            Jalur Umum
        </a>
        <a href="/admin/nilai?jalur=<?= urlencode('Jalur Beasiswa') ?>" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs font-bold border <?= $filterJalur === 'Jalur Beasiswa' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' ?>">
            Jalur Beasiswa
        </a>
    </div>
</div>

<!-- Daftar Peringkat Nilai (Mobile Cards + Desktop Table) -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <?php if (empty($ranking)): ?>
        <div class="p-8 text-center text-xs sm:text-sm text-slate-500">
            Belum ada data nilai calon taruna.
        </div>
    <?php else: ?>
        <!-- Tampilan Mobile (< md): Kartu Peringkat Rapi -->
        <div class="md:hidden divide-y divide-slate-200">
            <?php foreach ($ranking as $idx => $r): ?>
                <?php $rank = $idx + 1; ?>
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg font-extrabold text-xs shrink-0 <?= $rank <= 3 ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700' ?>">
                                #<?= $rank ?>
                            </span>
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-slate-900 break-words"><?= htmlspecialchars($r['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500">
                                    NISN: <?= htmlspecialchars($r['nisn']) ?> &bull; <?= htmlspecialchars($r['nama_sekolah_asal'] ?: 'Sekolah belum diisi') ?>
                                </p>
                            </div>
                        </div>
                        <span class="whitespace-nowrap shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <?= htmlspecialchars($r['jalur_pendaftaran'] ?? '-') ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-4 gap-1.5 bg-slate-50 rounded-xl p-2.5 border border-slate-100 text-center text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sem 1</span>
                            <span class="font-bold text-slate-800"><?= number_format((float)$r['rata_sem1'], 2) ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sem 2</span>
                            <span class="font-bold text-slate-800"><?= number_format((float)$r['rata_sem2'], 2) ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sem 3</span>
                            <span class="font-bold text-slate-800"><?= number_format((float)$r['rata_sem3'], 2) ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sem 4</span>
                            <span class="font-bold text-slate-800"><?= number_format((float)$r['rata_sem4'], 2) ?></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500">Rata-Rata Akhir:</span>
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 font-extrabold text-xs text-emerald-700">
                                <?= number_format((float)$r['rata_total'], 2) ?>
                            </span>
                        </div>
                        <a href="/admin/detail/<?= urlencode($r['id']) ?>" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tampilan Tablet/Desktop (md+): Tabel Proporsional -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-3 w-14 text-center whitespace-nowrap">Peringkat</th>
                        <th class="py-3.5 px-4">Calon Taruna</th>
                        <th class="py-3.5 px-3 whitespace-nowrap">Jalur</th>
                        <th class="py-3.5 px-2.5 text-center whitespace-nowrap">Sem 1</th>
                        <th class="py-3.5 px-2.5 text-center whitespace-nowrap">Sem 2</th>
                        <th class="py-3.5 px-2.5 text-center whitespace-nowrap">Sem 3</th>
                        <th class="py-3.5 px-2.5 text-center whitespace-nowrap">Sem 4</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Rata Akhir</th>
                        <th class="py-3.5 px-4 text-right whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($ranking as $idx => $r): ?>
                        <?php $rank = $idx + 1; ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-3 text-center align-middle whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg font-extrabold text-xs <?= $rank <= 3 ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700' ?>">
                                    #<?= $rank ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 align-middle">
                                <p class="font-bold text-slate-900"><?= htmlspecialchars($r['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500">
                                    NISN: <?= htmlspecialchars($r['nisn']) ?> &bull;
                                    <?= htmlspecialchars($r['nama_sekolah_asal'] ?: 'Sekolah belum diisi') ?>
                                </p>
                            </td>
                            <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <?= htmlspecialchars($r['jalur_pendaftaran'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-2.5 text-center font-medium align-middle whitespace-nowrap"><?= number_format((float)$r['rata_sem1'], 2) ?></td>
                            <td class="py-3.5 px-2.5 text-center font-medium align-middle whitespace-nowrap"><?= number_format((float)$r['rata_sem2'], 2) ?></td>
                            <td class="py-3.5 px-2.5 text-center font-medium align-middle whitespace-nowrap"><?= number_format((float)$r['rata_sem3'], 2) ?></td>
                            <td class="py-3.5 px-2.5 text-center font-medium align-middle whitespace-nowrap"><?= number_format((float)$r['rata_sem4'], 2) ?></td>
                            <td class="py-3.5 px-3 text-center align-middle whitespace-nowrap">
                                <span class="inline-block whitespace-nowrap px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 font-extrabold text-emerald-700">
                                    <?= number_format((float)$r['rata_total'], 2) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right align-middle whitespace-nowrap">
                                <a href="/admin/detail/<?= urlencode($r['id']) ?>" class="whitespace-nowrap font-bold text-emerald-700 hover:underline text-xs">
                                    Lihat &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>

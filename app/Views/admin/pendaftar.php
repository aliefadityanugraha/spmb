<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$pendaftar   = $data['pendaftar'] ?? [];
$filterQ     = $data['filter_q'] ?? '';
$filterJalur = $data['filter_jalur'] ?? '';
?>

<!-- Header & Filter Pencarian -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="min-w-0">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Data Pendaftar Calon Taruna</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                Menampilkan <strong><?= count($pendaftar) ?></strong> pendaftar terdaftar dalam sistem SPMB.
            </p>
        </div>
        <a href="/admin/exportCsv?jalur=<?= urlencode($filterJalur) ?>" class="whitespace-nowrap shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
            Unduh Data CSV / Excel
        </a>
    </div>

    <form action="/admin/pendaftar" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 pt-3 border-t border-slate-100">
        <div class="sm:col-span-2 lg:col-span-6">
            <input
                type="text"
                name="q"
                value="<?= htmlspecialchars($filterQ) ?>"
                placeholder="Cari Nama, NISN, NIK, No HP, atau Sekolah Asal..."
                class="block w-full rounded-xl border border-slate-300 bg-slate-50/70 focus:bg-white px-4 py-2.5 text-base sm:text-sm text-slate-900 focus:border-emerald-600 focus:outline-none"
            >
        </div>
        <div class="lg:col-span-3">
            <select
                name="jalur"
                class="block w-full rounded-xl border border-slate-300 bg-slate-50/70 focus:bg-white px-3.5 py-2.5 text-base sm:text-sm text-slate-900 focus:border-emerald-600 focus:outline-none"
            >
                <option value="">-- Semua Jalur --</option>
                <option value="Jalur Umum" <?= $filterJalur === 'Jalur Umum' ? 'selected' : '' ?>>Jalur Umum</option>
                <option value="Jalur Beasiswa" <?= $filterJalur === 'Jalur Beasiswa' ? 'selected' : '' ?>>Jalur Beasiswa</option>
            </select>
        </div>
        <div class="lg:col-span-3 flex items-center gap-2">
            <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition whitespace-nowrap">
                Filter Data
            </button>
            <?php if ($filterQ !== '' || $filterJalur !== ''): ?>
                <a href="/admin/pendaftar" class="py-2.5 px-3 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 whitespace-nowrap">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Daftar Pendaftar (Mobile Cards + Desktop Table) -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <?php if (empty($pendaftar)): ?>
        <div class="p-8 text-center text-xs sm:text-sm text-slate-500">
            Tidak ditemukan data calon taruna yang sesuai dengan pencarian.
        </div>
    <?php else: ?>
        <!-- Tampilan Mobile (< md): Kartu Rapi tanpa perlu geser tabel -->
        <div class="md:hidden divide-y divide-slate-200">
            <?php foreach ($pendaftar as $idx => $p): ?>
                <?php $biodataLengkap = !empty($p['tempat_lahir']) && !empty($p['nama_sekolah_asal']); ?>
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <span class="text-[11px] font-bold text-slate-400">#<?= $idx + 1 ?></span>
                            <p class="font-bold text-sm text-slate-900 break-words"><?= htmlspecialchars($p['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                NISN: <?= htmlspecialchars($p['nisn']) ?> &bull; WA: <?= htmlspecialchars($p['no_hp']) ?>
                            </p>
                        </div>
                        <?php if (!empty($p['jalur_pendaftaran'])): ?>
                            <span class="whitespace-nowrap shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <?= htmlspecialchars($p['jalur_pendaftaran']) ?>
                            </span>
                        <?php else: ?>
                            <span class="whitespace-nowrap shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Belum Pilih
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="text-xs text-slate-600 bg-slate-50/70 rounded-xl px-3 py-2 border border-slate-100">
                        <span class="text-slate-400">Asal Sekolah:</span>
                        <strong class="text-slate-800"><?= htmlspecialchars($p['nama_sekolah_asal'] ?: 'Belum diisi') ?></strong>
                        (<?= htmlspecialchars((string)($p['tahun_lulus'] ?? '-')) ?>)
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                        <div>
                            <span class="text-[10px] text-slate-400 block mb-0.5">Biodata</span>
                            <?php if ($biodataLengkap): ?>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lengkap</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Belum</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block mb-0.5">Berkas Valid</span>
                            <span class="font-bold text-slate-800"><?= (int)$p['berkas_valid'] ?> / <?= (int)$p['jumlah_berkas'] ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block mb-0.5">Rata Nilai</span>
                            <span class="font-extrabold text-emerald-700"><?= $p['rata_nilai'] !== null ? number_format((float)$p['rata_nilai'], 2) : '-' ?></span>
                        </div>
                    </div>

                    <a href="/admin/detail/<?= urlencode($p['id']) ?>" class="block w-full text-center py-2 px-3.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                        Detail &amp; Verifikasi
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tampilan Tablet/Desktop (md+): Tabel Proporsional -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-3 w-10 text-center">No</th>
                        <th class="py-3.5 px-4">Calon Taruna</th>
                        <th class="py-3.5 px-4">Sekolah &amp; Jalur</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Biodata</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Berkas</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Rata Nilai</th>
                        <th class="py-3.5 px-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($pendaftar as $idx => $p): ?>
                        <?php $biodataLengkap = !empty($p['tempat_lahir']) && !empty($p['nama_sekolah_asal']); ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-3 text-center font-semibold text-slate-500 align-middle"><?= $idx + 1 ?></td>
                            <td class="py-3.5 px-4 align-middle">
                                <p class="font-bold text-slate-900"><?= htmlspecialchars($p['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500 whitespace-nowrap">NISN: <?= htmlspecialchars($p['nisn']) ?> &bull; NIK: <?= htmlspecialchars($p['nik']) ?></p>
                                <p class="text-xs text-emerald-700 font-medium whitespace-nowrap">WA: <?= htmlspecialchars($p['no_hp']) ?></p>
                            </td>
                            <td class="py-3.5 px-4 align-middle">
                                <p class="font-semibold text-slate-800"><?= htmlspecialchars($p['nama_sekolah_asal'] ?: 'Sekolah belum diisi') ?></p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <?php if (!empty($p['jalur_pendaftaran'])): ?>
                                        <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <?= htmlspecialchars($p['jalur_pendaftaran']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            Belum Pilih
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-xs text-slate-400 whitespace-nowrap">Lulus: <?= htmlspecialchars((string)($p['tahun_lulus'] ?? '-')) ?></span>
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-center align-middle whitespace-nowrap">
                                <?php if ($biodataLengkap): ?>
                                    <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">Lengkap</span>
                                <?php else: ?>
                                    <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-3 text-center font-semibold text-slate-700 align-middle whitespace-nowrap">
                                <?= (int)$p['berkas_valid'] ?> / <?= (int)$p['jumlah_berkas'] ?>
                            </td>
                            <td class="py-3.5 px-3 text-center font-extrabold text-emerald-700 align-middle whitespace-nowrap">
                                <?= $p['rata_nilai'] !== null ? number_format((float)$p['rata_nilai'], 2) : '-' ?>
                            </td>
                            <td class="py-3.5 px-4 text-right align-middle whitespace-nowrap">
                                <a href="/admin/detail/<?= urlencode($p['id']) ?>" class="inline-block whitespace-nowrap px-3.5 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                                    Detail &amp; Verifikasi
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

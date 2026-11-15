<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$stats            = $data['stats'] ?? [];
$pendaftarTerbaru = $data['pendaftar_terbaru'] ?? [];
$berkasMenunggu   = $data['berkas_menunggu'] ?? [];
?>

<!-- Banner Sambutan Admin -->
<div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl p-5 sm:p-6 shadow-md">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="min-w-0">
            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 text-white mb-2">
                Dasbor Monitoring Panitia
            </span>
            <h2 class="text-lg sm:text-2xl font-extrabold text-white leading-snug">
                Sistem Penerimaan Murid Baru (SPMB)
            </h2>
            <p class="text-xs sm:text-sm text-emerald-50 mt-1">
                Pantau statistik pendaftar, verifikasi keabsahan berkas, dan peringkat nilai rapor calon taruna secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="/admin/pendaftar" class="whitespace-nowrap px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-emerald-800 hover:bg-emerald-50 shadow-sm transition">
                Lihat Semua Pendaftar
            </a>
            <a href="/admin/berkas?status=menunggu" class="whitespace-nowrap px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-800/60 text-white border border-white/25 hover:bg-emerald-800 transition">
                Verifikasi Berkas (<?= (int)($stats['berkas_menunggu'] ?? 0) ?>)
            </a>
        </div>
    </div>
</div>

<!-- Grid Kartu Statistik Utama -->
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Total Calon Taruna</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-slate-900">
            <?= (int)($stats['total_pendaftar'] ?? 0) ?>
        </p>
        <span class="text-[11px] text-emerald-700 font-semibold">
            <?= (int)($stats['biodata_lengkap'] ?? 0) ?> Biodata Lengkap
        </span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Jalur Umum</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-emerald-700">
            <?= (int)($stats['total_umum'] ?? 0) ?>
        </p>
        <span class="text-[11px] text-slate-500">Pendaftar Reguler</span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Jalur Beasiswa</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-teal-700">
            <?= (int)($stats['total_beasiswa'] ?? 0) ?>
        </p>
        <span class="text-[11px] text-slate-500">Prestasi &amp; Afirmasi</span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Berkas Menunggu</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-amber-600">
            <?= (int)($stats['berkas_menunggu'] ?? 0) ?>
        </p>
        <a href="/admin/berkas?status=menunggu" class="text-[11px] text-amber-700 font-bold hover:underline">
            Perlu Diverifikasi &rarr;
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Berkas Valid</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-emerald-600">
            <?= (int)($stats['berkas_valid'] ?? 0) ?>
        </p>
        <span class="text-[11px] text-slate-500">Dari <?= (int)($stats['total_berkas'] ?? 0) ?> Total Berkas</span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col justify-between min-w-0">
        <span class="text-xs font-semibold text-slate-500">Berkas Ditolak / Revisi</span>
        <p class="my-1.5 text-2xl sm:text-3xl font-extrabold text-rose-600">
            <?= (int)($stats['berkas_ditolak'] ?? 0) ?>
        </p>
        <span class="text-[11px] text-slate-500">Menunggu Perbaikan</span>
    </div>
</div>

<!-- Antrean Berkas yang Menunggu Verifikasi -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Antrean Verifikasi Berkas Terbaru</h3>
            <p class="text-xs text-slate-500">Dokumen calon taruna yang berstatus menunggu pemeriksaan panitia.</p>
        </div>
        <a href="/admin/berkas" class="whitespace-nowrap text-xs font-bold text-emerald-700 hover:underline shrink-0">
            Semua Berkas &rarr;
        </a>
    </div>

    <?php if (empty($berkasMenunggu)): ?>
        <div class="p-6 text-center text-xs sm:text-sm text-slate-500">
            Tidak ada antrean berkas yang menunggu verifikasi saat ini.
        </div>
    <?php else: ?>
        <!-- Tampilan Mobile (< md): Kartu Rapi -->
        <div class="md:hidden divide-y divide-slate-200">
            <?php foreach ($berkasMenunggu as $bk): ?>
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-900 break-words"><?= htmlspecialchars($bk['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                NISN: <?= htmlspecialchars($bk['nisn']) ?> &bull; <?= htmlspecialchars($bk['jalur_pendaftaran'] ?? '-') ?>
                            </p>
                        </div>
                        <a href="<?= htmlspecialchars($bk['file_path']) ?>" target="_blank" class="whitespace-nowrap shrink-0 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                            Lihat File
                        </a>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100 flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-slate-800 break-words"><?= htmlspecialchars($bk['nama_berkas']) ?></span>
                        <?php if (!empty($bk['tingkat'])): ?>
                            <span class="whitespace-nowrap shrink-0 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <?= htmlspecialchars($bk['tingkat']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="/admin/verifikasiBerkas" method="POST" class="flex-1">
                            <input type="hidden" name="berkas_id" value="<?= (int)$bk['id'] ?>">
                            <input type="hidden" name="status_verifikasi" value="valid">
                            <input type="hidden" name="redirect_to" value="/admin">
                            <button type="submit" class="w-full py-2 px-3 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                                Setujui (Valid)
                            </button>
                        </form>
                        <a href="/admin/detail/<?= urlencode($bk['user_id']) ?>" class="py-2 px-3.5 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold">
                            Detail
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
                        <th class="py-3 px-4">Calon Taruna</th>
                        <th class="py-3 px-4">Dokumen</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Pratinjau</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($berkasMenunggu as $bk): ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 align-middle">
                                <p class="font-bold text-slate-900"><?= htmlspecialchars($bk['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500">NISN: <?= htmlspecialchars($bk['nisn']) ?> &bull; <?= htmlspecialchars($bk['jalur_pendaftaran'] ?? '-') ?></p>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <p class="font-semibold text-slate-800"><?= htmlspecialchars($bk['nama_berkas']) ?></p>
                                <?php if (!empty($bk['tingkat'])): ?>
                                    <span class="inline-block whitespace-nowrap mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Tingkat: <?= htmlspecialchars($bk['tingkat']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-center align-middle whitespace-nowrap">
                                <a href="<?= htmlspecialchars($bk['file_path']) ?>" target="_blank" class="inline-block whitespace-nowrap px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100">
                                    Lihat File
                                </a>
                            </td>
                            <td class="py-3 px-4 text-right align-middle whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <form action="/admin/verifikasiBerkas" method="POST" class="inline">
                                        <input type="hidden" name="berkas_id" value="<?= (int)$bk['id'] ?>">
                                        <input type="hidden" name="status_verifikasi" value="valid">
                                        <input type="hidden" name="redirect_to" value="/admin">
                                        <button type="submit" class="whitespace-nowrap px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                                            Setujui
                                        </button>
                                    </form>
                                    <a href="/admin/detail/<?= urlencode($bk['user_id']) ?>" class="whitespace-nowrap px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold hover:bg-slate-200">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Tabel Pendaftar Terbaru -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Pendaftar Calon Taruna Terbaru</h3>
            <p class="text-xs text-slate-500">Daftar akun calon taruna yang baru mendaftar pada sistem.</p>
        </div>
        <a href="/admin/pendaftar" class="whitespace-nowrap text-xs font-bold text-emerald-700 hover:underline shrink-0">
            Kelola Semua &rarr;
        </a>
    </div>

    <?php if (empty($pendaftarTerbaru)): ?>
        <div class="p-6 text-center text-xs sm:text-sm text-slate-500">
            Belum ada data calon taruna yang terdaftar.
        </div>
    <?php else: ?>
        <!-- Tampilan Mobile (< md): Kartu Rapi -->
        <div class="md:hidden divide-y divide-slate-200">
            <?php foreach ($pendaftarTerbaru as $p): ?>
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-900 break-words"><?= htmlspecialchars($p['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-500 mt-0.5">NISN: <?= htmlspecialchars($p['nisn']) ?> &bull; WA: <?= htmlspecialchars($p['no_hp']) ?></p>
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

                    <div class="grid grid-cols-3 gap-2 bg-slate-50 rounded-xl p-2.5 border border-slate-100 text-center text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sekolah</span>
                            <span class="font-semibold text-slate-700 truncate block"><?= htmlspecialchars($p['nama_sekolah_asal'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Berkas Valid</span>
                            <span class="font-bold text-slate-800"><?= (int)$p['berkas_valid'] ?> / <?= (int)$p['jumlah_berkas'] ?></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Rata Nilai</span>
                            <span class="font-extrabold text-emerald-700"><?= $p['rata_nilai'] !== null ? number_format((float)$p['rata_nilai'], 2) : '-' ?></span>
                        </div>
                    </div>

                    <a href="/admin/detail/<?= urlencode($p['id']) ?>" class="block w-full text-center py-2 px-3 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                        Periksa Data Pendaftar
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tampilan Tablet/Desktop (md+): Tabel Proporsional -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Nama &amp; NISN</th>
                        <th class="py-3 px-4 whitespace-nowrap">Jalur</th>
                        <th class="py-3 px-4">Asal Sekolah</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Berkas</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Rata Nilai</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($pendaftarTerbaru as $p): ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 align-middle">
                                <p class="font-bold text-slate-900"><?= htmlspecialchars($p['nama_lengkap']) ?></p>
                                <p class="text-xs text-slate-500">NISN: <?= htmlspecialchars($p['nisn']) ?> &bull; HP: <?= htmlspecialchars($p['no_hp']) ?></p>
                            </td>
                            <td class="py-3 px-4 align-middle whitespace-nowrap">
                                <?php if (!empty($p['jalur_pendaftaran'])): ?>
                                    <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <?= htmlspecialchars($p['jalur_pendaftaran']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block whitespace-nowrap px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Pilih
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 align-middle text-slate-700">
                                <?= htmlspecialchars($p['nama_sekolah_asal'] ?? 'Belum diisi') ?>
                            </td>
                            <td class="py-3 px-4 align-middle text-center font-semibold text-slate-700 whitespace-nowrap">
                                <?= (int)$p['berkas_valid'] ?> / <?= (int)$p['jumlah_berkas'] ?> Valid
                            </td>
                            <td class="py-3 px-4 align-middle text-center font-extrabold text-emerald-700 whitespace-nowrap">
                                <?= $p['rata_nilai'] !== null ? number_format((float)$p['rata_nilai'], 2) : '-' ?>
                            </td>
                            <td class="py-3 px-4 align-middle text-right whitespace-nowrap">
                                <a href="/admin/detail/<?= urlencode($p['id']) ?>" class="inline-block whitespace-nowrap px-3.5 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                                    Periksa
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

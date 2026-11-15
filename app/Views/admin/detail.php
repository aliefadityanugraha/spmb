<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$siswa       = $data['siswa'] ?? [];
$bio         = $data['biodata'] ?? [];
$berkasList  = $data['berkas_list'] ?? [];
$mapelList   = $data['mapel_list'] ?? [];
$nilaiMatrix = $data['nilai_matrix'] ?? [];
?>

<!-- Header Navigasi Detail -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 min-w-0">
        <div class="w-16 h-20 rounded-xl border border-slate-300 bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center">
            <?php if (!empty($bio['pas_foto'])): ?>
                <img src="<?= htmlspecialchars($bio['pas_foto']) ?>" alt="Pas Foto" loading="lazy" decoding="async" class="w-full h-full object-cover">
            <?php else: ?>
                <span class="text-[10px] font-bold text-slate-400 text-center px-1">Belum Ada Foto</span>
            <?php endif; ?>
        </div>
        <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-base sm:text-xl font-extrabold text-slate-900 break-words"><?= htmlspecialchars($siswa['nama_lengkap'] ?? '') ?></h2>
                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <?= htmlspecialchars($siswa['jalur_pendaftaran'] ?? 'Belum Pilih Jalur') ?>
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 mt-1.5">
                <span class="whitespace-nowrap">NISN: <strong class="text-slate-800"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></strong></span>
                <span class="whitespace-nowrap">NIK: <strong class="text-slate-800"><?= htmlspecialchars($siswa['nik'] ?? '-') ?></strong></span>
                <span class="whitespace-nowrap">WA: <strong class="text-emerald-700"><?= htmlspecialchars($siswa['no_hp'] ?? '-') ?></strong></span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2 shrink-0">
        <a href="/admin/pendaftar" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200">
            &larr; Kembali
        </a>
        <form action="/admin/hapusPendaftar" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data calon taruna ini secara permanen?');">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($siswa['id']) ?>">
            <button type="submit" class="whitespace-nowrap px-3.5 py-2 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100">
                Hapus Pendaftar
            </button>
        </form>
    </div>
</div>

<!-- 1. Ringkasan Biodata Lengkap & Ubah Jalur -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 items-start">
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4 min-w-0">
        <h3 class="text-sm sm:text-base font-bold text-emerald-800 border-b border-slate-100 pb-2.5">
            A. Data Biodata Calon Taruna
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Tempat, Tanggal Lahir</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= htmlspecialchars($bio['tempat_lahir'] ?? '-') ?>,
                    <?= !empty($bio['tanggal_lahir']) ? date('d-m-Y', strtotime($bio['tanggal_lahir'])) : '-' ?>
                </span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Jenis Kelamin / Agama</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= (($bio['jenis_kelamin'] ?? '') === 'L') ? 'Laki-laki' : ((($bio['jenis_kelamin'] ?? '') === 'P') ? 'Perempuan' : '-') ?>
                    / <?= htmlspecialchars($bio['agama'] ?? '-') ?>
                </span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Asal Sekolah (NPSN)</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= htmlspecialchars($bio['nama_sekolah_asal'] ?? '-') ?>
                    (<?= htmlspecialchars($bio['npsn_sekolah_asal'] ?? '-') ?>)
                </span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Nama Ayah / Nama Ibu</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= htmlspecialchars($bio['nama_ayah'] ?? '-') ?> / <?= htmlspecialchars($bio['nama_ibu'] ?? '-') ?>
                </span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">No. HP Ortu &amp; Penghasilan</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= htmlspecialchars($bio['no_hp_ortu'] ?? '-') ?> (<?= htmlspecialchars($bio['penghasilan_ortu'] ?? '-') ?>)
                </span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 min-w-0">
                <span class="text-[11px] font-medium text-slate-400 block mb-0.5">Alamat Domisili</span>
                <span class="font-semibold text-slate-800 break-words">
                    <?= htmlspecialchars($bio['alamat_lengkap'] ?? '-') ?>,
                    <?= htmlspecialchars($bio['kelurahan_desa'] ?? '') ?>,
                    <?= htmlspecialchars($bio['kecamatan'] ?? '') ?>,
                    <?= htmlspecialchars($bio['kabupaten_kota'] ?? '') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Ubah Jalur Pendaftaran oleh Admin -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4 min-w-0">
        <h3 class="text-sm sm:text-base font-bold text-emerald-800 border-b border-slate-100 pb-2.5">
            Pengaturan Jalur Pendaftaran
        </h3>
        <form action="/admin/ubahJalur" method="POST" class="space-y-3">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($siswa['id']) ?>">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jalur Pendaftaran</label>
                <select name="jalur_pendaftaran" class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-emerald-600 focus:outline-none">
                    <option value="Jalur Umum" <?= ($siswa['jalur_pendaftaran'] ?? '') === 'Jalur Umum' ? 'selected' : '' ?>>Jalur Umum</option>
                    <option value="Jalur Beasiswa" <?= ($siswa['jalur_pendaftaran'] ?? '') === 'Jalur Beasiswa' ? 'selected' : '' ?>>Jalur Beasiswa</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                Simpan Perubahan Jalur
            </button>
        </form>
    </div>
</div>

<!-- 2. Daftar Berkas & Form Verifikasi -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
    <h3 class="text-sm sm:text-base font-bold text-emerald-800 border-b border-slate-100 pb-2.5">
        B. Pemeriksaan &amp; Verifikasi Berkas Persyaratan (<?= count($berkasList) ?> File)
    </h3>

    <?php if (empty($berkasList)): ?>
        <p class="text-xs sm:text-sm text-slate-500">Calon taruna ini belum mengunggah berkas apa pun.</p>
    <?php else: ?>
        <div class="space-y-3.5">
            <?php foreach ($berkasList as $bk): ?>
                <?php
                $st = $bk['status_verifikasi'] ?? 'menunggu';
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                if ($st === 'valid') $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                elseif ($st === 'ditolak' || $st === 'perbaikan') $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                ?>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-slate-900 break-words"><?= htmlspecialchars($bk['nama_berkas']) ?></span>
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase border <?= $badgeClass ?>">
                                    <?= htmlspecialchars($st) ?>
                                </span>
                                <?php if (!empty($bk['tingkat'])): ?>
                                    <span class="whitespace-nowrap px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        Tingkat: <?= htmlspecialchars($bk['tingkat']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-slate-500">
                                Jenis: <code class="text-slate-700"><?= htmlspecialchars($bk['jenis_berkas']) ?></code> &bull;
                                Ukuran: <?= (int)$bk['ukuran_kb'] ?> KB
                            </p>
                        </div>

                        <a href="<?= htmlspecialchars($bk['file_path']) ?>" target="_blank" class="whitespace-nowrap self-start sm:self-center shrink-0 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100">
                            Buka / Lihat File &rarr;
                        </a>
                    </div>

                    <form action="/admin/verifikasiBerkas" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 pt-2.5 border-t border-slate-200/80">
                        <input type="hidden" name="berkas_id" value="<?= (int)$bk['id'] ?>">
                        <input type="hidden" name="redirect_to" value="/admin/detail/<?= urlencode($siswa['id']) ?>">
                        <div class="sm:col-span-6">
                            <input
                                type="text"
                                name="catatan_verifikator"
                                value="<?= htmlspecialchars($bk['catatan_verifikator'] ?? '') ?>"
                                placeholder="Catatan verifikator (opsional)"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-emerald-600 focus:outline-none"
                            >
                        </div>
                        <div class="sm:col-span-4">
                            <select name="status_verifikasi" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-800 focus:border-emerald-600 focus:outline-none">
                                <option value="menunggu" <?= $st === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                <option value="valid" <?= $st === 'valid' ? 'selected' : '' ?>>Valid (Disetujui)</option>
                                <option value="perbaikan" <?= $st === 'perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan</option>
                                <option value="ditolak" <?= $st === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="w-full py-2 px-3.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- 3. Tabel Rekapitulasi Nilai Rapor (Semester 1 s.d. 4) -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
    <h3 class="text-sm sm:text-base font-bold text-emerald-800 border-b border-slate-100 pb-2.5">
        C. Rekapitulasi Nilai Rapor (Semester 1 s.d. 4)
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[540px] text-left border-collapse text-xs sm:text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                    <th class="py-2.5 px-3 text-center w-10">No</th>
                    <th class="py-2.5 px-3">Mata Pelajaran</th>
                    <th class="py-2.5 px-3 text-center whitespace-nowrap">Sem 1</th>
                    <th class="py-2.5 px-3 text-center whitespace-nowrap">Sem 2</th>
                    <th class="py-2.5 px-3 text-center whitespace-nowrap">Sem 3</th>
                    <th class="py-2.5 px-3 text-center whitespace-nowrap">Sem 4</th>
                    <th class="py-2.5 px-3 text-center whitespace-nowrap">Rata-Rata</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php
                $no = 1;
                $grandSum = 0;
                $grandCnt = 0;
                ?>
                <?php foreach ($mapelList as $kode => $namaMapel): ?>
                    <?php
                    $sumM = 0;
                    $cntM = 0;
                    ?>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2 px-3 text-center text-slate-500"><?= $no++ ?></td>
                        <td class="py-2 px-3 font-medium text-slate-800"><?= htmlspecialchars($namaMapel) ?></td>
                        <?php for ($s = 1; $s <= 4; $s++): ?>
                            <?php
                            $v = $nilaiMatrix[$s][$kode] ?? null;
                            if ($v !== null) {
                                $sumM += (float)$v;
                                $cntM++;
                                $grandSum += (float)$v;
                                $grandCnt++;
                            }
                            ?>
                            <td class="py-2 px-3 text-center whitespace-nowrap"><?= $v !== null ? number_format((float)$v, 2) : '-' ?></td>
                        <?php endfor; ?>
                        <td class="py-2 px-3 text-center font-bold text-emerald-700 bg-emerald-50/40 whitespace-nowrap">
                            <?= $cntM > 0 ? number_format($sumM / $cntM, 2) : '-' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-emerald-50 font-extrabold text-emerald-900 border-t border-emerald-200">
                    <td colspan="6" class="py-2.5 px-3 text-right">Rata-Rata Keseluruhan (Semester 1–4):</td>
                    <td class="py-2.5 px-3 text-center text-sm whitespace-nowrap">
                        <?= $grandCnt > 0 ? number_format($grandSum / $grandCnt, 2) : '0.00' ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>

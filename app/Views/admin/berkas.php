<?php require_once __DIR__ . '/layout_header.php'; ?>

<?php
$berkasList   = $data['berkas_list'] ?? [];
$filterStatus = $data['filter_status'] ?? '';
$filterQ      = $data['filter_q'] ?? '';
$stats        = $data['stats'] ?? [];
?>

<!-- Header & Filter Status Berkas -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="min-w-0">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Verifikasi Berkas Persyaratan</h2>
            <p class="text-xs sm:text-sm text-slate-500">
                Periksa keabsahan dokumen wajib dan sertifikat prestasi yang diunggah oleh calon taruna.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 text-xs font-bold shrink-0">
            <a href="/admin/berkas" class="whitespace-nowrap px-3 py-1.5 rounded-xl border <?= $filterStatus === '' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' ?>">
                Semua (<?= (int)($stats['total_berkas'] ?? 0) ?>)
            </a>
            <a href="/admin/berkas?status=menunggu" class="whitespace-nowrap px-3 py-1.5 rounded-xl border <?= $filterStatus === 'menunggu' ? 'bg-amber-500 text-white border-amber-500' : 'bg-amber-50 text-amber-800 border-amber-200' ?>">
                Menunggu (<?= (int)($stats['berkas_menunggu'] ?? 0) ?>)
            </a>
            <a href="/admin/berkas?status=valid" class="whitespace-nowrap px-3 py-1.5 rounded-xl border <?= $filterStatus === 'valid' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-emerald-50 text-emerald-800 border-emerald-200' ?>">
                Valid (<?= (int)($stats['berkas_valid'] ?? 0) ?>)
            </a>
        </div>
    </div>

    <form action="/admin/berkas" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 pt-3 border-t border-slate-100">
        <div class="sm:col-span-2 lg:col-span-6">
            <input
                type="text"
                name="q"
                value="<?= htmlspecialchars($filterQ) ?>"
                placeholder="Cari Nama Pendaftar, NISN, atau Nama Berkas..."
                class="block w-full rounded-xl border border-slate-300 bg-slate-50/70 focus:bg-white px-4 py-2.5 text-base sm:text-sm text-slate-900 focus:border-emerald-600 focus:outline-none"
            >
        </div>
        <div class="lg:col-span-4">
            <select name="status" class="block w-full rounded-xl border border-slate-300 bg-slate-50/70 focus:bg-white px-3.5 py-2.5 text-base sm:text-sm text-slate-900 focus:border-emerald-600 focus:outline-none">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" <?= $filterStatus === 'menunggu' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                <option value="valid" <?= $filterStatus === 'valid' ? 'selected' : '' ?>>Valid (Disetujui)</option>
                <option value="perbaikan" <?= $filterStatus === 'perbaikan' ? 'selected' : '' ?>>Perlu Perbaikan</option>
                <option value="ditolak" <?= $filterStatus === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select>
        </div>
        <div class="lg:col-span-2">
            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition whitespace-nowrap">
                Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Daftar Berkas -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <?php if (empty($berkasList)): ?>
        <div class="p-8 text-center text-xs sm:text-sm text-slate-500">
            Tidak ada berkas yang sesuai dengan filter saat ini.
        </div>
    <?php else: ?>
        <div class="divide-y divide-slate-200">
            <?php foreach ($berkasList as $bk): ?>
                <?php
                $st = $bk['status_verifikasi'] ?? 'menunggu';
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                if ($st === 'valid') $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                elseif ($st === 'ditolak' || $st === 'perbaikan') $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                ?>
                <div class="p-4 sm:p-5 hover:bg-slate-50/60 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="/admin/detail/<?= urlencode($bk['user_id']) ?>" class="font-extrabold text-sm text-emerald-700 hover:underline break-words">
                                    <?= htmlspecialchars($bk['nama_lengkap']) ?>
                                </a>
                                <span class="text-xs text-slate-400 whitespace-nowrap">&bull; NISN: <?= htmlspecialchars($bk['nisn']) ?></span>
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase border <?= $badgeClass ?>">
                                    <?= htmlspecialchars($st) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-xs sm:text-sm font-semibold text-slate-800 break-words">
                                    <?= htmlspecialchars($bk['nama_berkas']) ?>
                                </p>
                                <?php if (!empty($bk['tingkat'])): ?>
                                    <span class="whitespace-nowrap px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        Tingkat: <?= htmlspecialchars($bk['tingkat']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <p class="text-xs text-slate-500">
                                Ukuran: <?= (int)$bk['ukuran_kb'] ?> KB &bull; Jalur: <?= htmlspecialchars($bk['jalur_pendaftaran'] ?? '-') ?>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 self-start">
                            <a href="<?= htmlspecialchars($bk['file_path']) ?>" target="_blank" class="whitespace-nowrap px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100">
                                Lihat File Unggahan &rarr;
                            </a>
                            <a href="/admin/detail/<?= urlencode($bk['user_id']) ?>" class="whitespace-nowrap px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold hover:bg-slate-200">
                                Profil Siswa
                            </a>
                        </div>
                    </div>

                    <form action="/admin/verifikasiBerkas" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 pt-2.5 border-t border-slate-100">
                        <input type="hidden" name="berkas_id" value="<?= (int)$bk['id'] ?>">
                        <input type="hidden" name="redirect_to" value="/admin/berkas?status=<?= urlencode($filterStatus) ?>&q=<?= urlencode($filterQ) ?>">
                        <div class="sm:col-span-6">
                            <input
                                type="text"
                                name="catatan_verifikator"
                                value="<?= htmlspecialchars($bk['catatan_verifikator'] ?? '') ?>"
                                placeholder="Catatan panitia (opsional, misal: Foto buram)"
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
                            <button type="submit" class="w-full py-2 px-4 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout_footer.php'; ?>

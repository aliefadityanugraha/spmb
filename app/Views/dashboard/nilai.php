<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Input Nilai Rapor Semester 1 s.d. Semester 4 Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
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
    $mapelList   = $data['mapel_list'] ?? [];
    $nilaiMatrix = $data['nilai_matrix'] ?? [1 => [], 2 => [], 3 => [], 4 => []];
    $errors      = $data['errors'] ?? [];
    $isEditMode  = !empty($data['is_edit_mode']);
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
                <a href="/biodata" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    1. Biodata
                </a>
                <a href="/berkas" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200">
                    2. Berkas
                </a>
                <a href="/nilai" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm">
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
                        <a href="/biodata" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>1. Biodata</span>
                        </a>
                        <a href="/berkas" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <span>2. Upload Berkas</span>
                        </a>
                        <a href="/nilai" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span>3. Nilai Raport</span>
                            <span class="w-2 h-2 rounded-full <?= $isEditMode ? 'bg-emerald-500' : 'bg-amber-500' ?>"></span>
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

            <!-- Area Form Tabel Nilai Raport -->
            <section class="lg:col-span-3 space-y-5 sm:space-y-6">

                <!-- Header Halaman -->
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Input Nilai Raport (Sem 1 s.d. 4)</h2>
                            <?php if ($isEditMode): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Mode Edit (Nilai Tersimpan)
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Belum Diisi
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Masukkan nilai pengetahuan (skala <strong>0 – 100</strong>) untuk <strong>10 mata pelajaran</strong> pada masing-masing tab <strong>Semester 1 hingga Semester 4</strong>.
                        </p>
                    </div>

                    <!-- Rata-Rata Keseluruhan -->
                    <div class="w-full sm:w-auto shrink-0 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5 flex sm:block items-center justify-between text-center">
                        <span class="text-xs sm:text-[11px] text-emerald-800 font-medium block">Rata-Rata Total (Sem 1–4)</span>
                        <span id="rata-total-semua" class="text-base sm:text-lg font-extrabold text-emerald-700">0.00</span>
                    </div>
                </div>

                <!-- Notifikasi Flash -->
                <?php if (!empty($data['success'])): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 sm:p-4 text-xs sm:text-sm text-emerald-800 font-medium flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><?= htmlspecialchars($data['success']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['error'])): ?>
                    <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 text-xs sm:text-sm text-rose-700 font-medium">
                        <?= htmlspecialchars($data['error']) ?>
                    </div>
                <?php endif; ?>

                <form action="/nilai/simpan" method="POST" id="form-nilai-raport" class="space-y-5 sm:space-y-6" novalidate>

                    <!-- Navigasi 4 Tab Semester (Semester 1 - 4) -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-sm">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" role="tablist">
                            <?php for ($sem = 1; $sem <= 4; $sem++): ?>
                                <?php
                                $semHasError = false;
                                foreach ($errors as $errKey => $msg) {
                                    if (strpos($errKey, $sem . '_') === 0) {
                                        $semHasError = true;
                                        break;
                                    }
                                }
                                ?>
                                <button
                                    type="button"
                                    id="tab-btn-<?= $sem ?>"
                                    onclick="bukaTabSemester(<?= $sem ?>)"
                                    class="tab-semester-btn flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition <?= $sem === 1 ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-700' ?>"
                                >
                                    <span>Semester <?= $sem ?></span>
                                    <span id="badge-rata-sem-<?= $sem ?>" class="px-1.5 sm:px-2 py-0.5 rounded-md text-[10px] sm:text-[11px] font-semibold <?= $sem === 1 ? 'bg-white/20 text-white' : 'bg-white text-emerald-700 border border-slate-200' ?>">
                                        0.00
                                    </span>
                                    <?php if ($semHasError): ?>
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <?php endif; ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Panel Konten Tabel untuk Semester 1 s.d. 4 -->
                    <?php for ($sem = 1; $sem <= 4; $sem++): ?>
                        <div
                            id="panel-semester-<?= $sem ?>"
                            class="panel-semester bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm <?= $sem === 1 ? '' : 'hidden' ?>"
                        >
                            <!-- Sub-header Semester -->
                            <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-emerald-50/50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-emerald-800">
                                        Daftar Nilai Rapor &mdash; Semester <?= $sem ?>
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Isi nilai angka skala 0 – 100 (contoh: <code class="text-slate-700 font-semibold">85</code> atau <code class="text-slate-700 font-semibold">88.50</code>).
                                    </p>
                                </div>
                                <div class="text-xs text-slate-600">
                                    Rata-rata Semester <?= $sem ?>:
                                    <strong id="teks-rata-sem-<?= $sem ?>" class="text-emerald-700 text-sm ml-1">0.00</strong>
                                </div>
                            </div>

                            <!-- Tabel HTML Responsif 10 Mata Pelajaran -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] sm:text-xs uppercase tracking-wider text-slate-500">
                                            <th class="py-3 px-2.5 sm:px-6 w-10 sm:w-14 text-center">No</th>
                                            <th class="py-3 px-2.5 sm:px-6">Mata Pelajaran</th>
                                            <th class="hidden sm:table-cell py-3 px-4 w-36 text-center">Kode Mapel</th>
                                            <th class="py-3 px-3 sm:px-6 w-28 sm:w-48 text-center">Nilai Sem <?= $sem ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 text-xs sm:text-sm">
                                        <?php $no = 1; ?>
                                        <?php foreach ($mapelList as $kodeMapel => $namaLengkapMapel): ?>
                                            <?php
                                            $errKey   = "{$sem}_{$kodeMapel}";
                                            $hasErr   = isset($errors[$errKey]);
                                            $valSaved = $nilaiMatrix[$sem][$kodeMapel] ?? '';
                                            ?>
                                            <tr class="hover:bg-emerald-50/30 transition">
                                                <td class="py-3 px-2.5 sm:px-6 text-center font-semibold text-slate-500">
                                                    <?= $no++ ?>
                                                </td>
                                                <td class="py-3 px-2.5 sm:px-6 font-medium text-slate-800">
                                                    <span class="sm:hidden inline-block mb-1 px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700">
                                                        <?= htmlspecialchars($kodeMapel) ?>
                                                    </span>
                                                    <label for="nilai_<?= $sem ?>_<?= md5($kodeMapel) ?>" class="cursor-pointer block leading-snug">
                                                        <?= htmlspecialchars($namaLengkapMapel) ?>
                                                    </label>
                                                    <?php if ($hasErr): ?>
                                                        <p class="mt-1 text-xs text-rose-600 font-medium"><?= htmlspecialchars($errors[$errKey]) ?></p>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="hidden sm:table-cell py-3.5 px-4 text-center">
                                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700">
                                                        <?= htmlspecialchars($kodeMapel) ?>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3 sm:px-6">
                                                    <input
                                                        id="nilai_<?= $sem ?>_<?= md5($kodeMapel) ?>"
                                                        type="number"
                                                        name="nilai[<?= $sem ?>][<?= htmlspecialchars($kodeMapel) ?>]"
                                                        data-semester="<?= $sem ?>"
                                                        step="0.01"
                                                        min="0"
                                                        max="100"
                                                        required
                                                        placeholder="0-100"
                                                        value="<?= htmlspecialchars((string)$valSaved) ?>"
                                                        oninput="hitungRataRata()"
                                                        class="input-nilai-raport block w-full text-center font-bold rounded-xl border <?= $hasErr ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600' ?> bg-slate-50/70 focus:bg-white px-2 sm:px-3 py-2.5 text-base sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 transition"
                                                    >
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Navigasi Antar Tab Semester di Bawah Tabel -->
                            <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-2">
                                <?php if ($sem > 1): ?>
                                    <button
                                        type="button"
                                        onclick="bukaTabSemester(<?= $sem - 1 ?>)"
                                        class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-100 transition"
                                    >
                                        &larr; Sem <?= $sem - 1 ?>
                                    </button>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>

                                <?php if ($sem < 4): ?>
                                    <button
                                        type="button"
                                        onclick="bukaTabSemester(<?= $sem + 1 ?>)"
                                        class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition"
                                    >
                                        Lanjut Sem <?= $sem + 1 ?> &rarr;
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-emerald-700 font-bold text-right">
                                        Semester terakhir &mdash; Klik Simpan di bawah.
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <!-- Tombol Submit Utama -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                        <p class="text-xs text-slate-500 text-center sm:text-left">
                            Pastikan nilai pada <strong>keempat tab (Semester 1, 2, 3, dan 4)</strong> telah terisi lengkap sebelum menekan tombol simpan.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                            <a href="/dashboard" class="w-full sm:w-auto text-center px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Kembali
                            </a>
                            <button
                                type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 rounded-xl shadow-md shadow-emerald-600/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
                            >
                                <?= $isEditMode ? 'Perbarui Nilai Raport (Sem 1–4)' : 'Simpan Nilai Raport (Sem 1–4)' ?>
                            </button>
                        </div>
                    </div>

                </form>

            </section>
        </div>
    </div>

    <script>
        function bukaTabSemester(semesterAktif) {
            for (let s = 1; s <= 4; s++) {
                const panel = document.getElementById('panel-semester-' + s);
                const btn   = document.getElementById('tab-btn-' + s);
                const badge = document.getElementById('badge-rata-sem-' + s);

                if (s === semesterAktif) {
                    panel.classList.remove('hidden');
                    btn.className = 'tab-semester-btn flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition bg-emerald-600 text-white shadow-sm';
                    if (badge) {
                        badge.className = 'px-1.5 sm:px-2 py-0.5 rounded-md text-[10px] sm:text-[11px] font-semibold bg-white/20 text-white';
                    }
                } else {
                    panel.classList.add('hidden');
                    btn.className = 'tab-semester-btn flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-700';
                    if (badge) {
                        badge.className = 'px-1.5 sm:px-2 py-0.5 rounded-md text-[10px] sm:text-[11px] font-semibold bg-white text-emerald-700 border border-slate-200';
                    }
                }
            }
        }

        function hitungRataRata() {
            let totalSemua = 0;
            let countSemua = 0;

            for (let s = 1; s <= 4; s++) {
                const inputs = document.querySelectorAll(`input.input-nilai-raport[data-semester="${s}"]`);
                let sumSem = 0;
                let countSem = 0;

                inputs.forEach(inp => {
                    const val = parseFloat(inp.value);
                    if (!isNaN(val) && val >= 0 && val <= 100) {
                        sumSem += val;
                        countSem++;
                        totalSemua += val;
                        countSemua++;
                    }
                });

                const avgSem = countSem > 0 ? (sumSem / countSem).toFixed(2) : '0.00';
                const badge  = document.getElementById('badge-rata-sem-' + s);
                const teks   = document.getElementById('teks-rata-sem-' + s);

                if (badge) badge.textContent = avgSem;
                if (teks) teks.textContent = avgSem;
            }

            const avgTotal = countSemua > 0 ? (totalSemua / countSemua).toFixed(2) : '0.00';
            const elTotal  = document.getElementById('rata-total-semua');
            if (elTotal) elTotal.textContent = avgTotal;
        }

        document.getElementById('form-nilai-raport').addEventListener('submit', function(e) {
            for (let s = 1; s <= 4; s++) {
                const inputs = document.querySelectorAll(`input.input-nilai-raport[data-semester="${s}"]`);
                for (const inp of inputs) {
                    const val = parseFloat(inp.value);
                    if (inp.value.trim() === '' || isNaN(val) || val < 0 || val > 100) {
                        e.preventDefault();
                        bukaTabSemester(s);
                        setTimeout(() => inp.focus(), 50);
                        return;
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            hitungRataRata();
        });
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Cetak Rekapitulasi Nilai Rapor & Surat Pernyataan Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
    $data['meta_robots']      = 'noindex, nofollow';
    require __DIR__ . '/../partials/seo_head.php';
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .print-sheet {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen py-8 px-4 sm:px-6">

    <?php
    $user             = $data['user'] ?? [];
    $bio              = $data['biodata'] ?? [];
    $mapelList        = $data['mapel_list'] ?? [];
    $nilaiMatrix      = $data['nilai_matrix'] ?? [];
    $totalPerSemester = $data['total_per_semester'] ?? [];
    $rataPerSemester  = $data['rata_per_semester'] ?? [];
    $rataPerMapel     = $data['rata_per_mapel'] ?? [];
    $grandTotal       = $data['grand_total'] ?? 0;
    $grandAverage     = $data['grand_average'] ?? 0;
    $nomorPeserta     = $data['nomor_peserta'] ?? '-';
    ?>

    <!-- Action Bar (Disembunyikan saat mode cetak kertas / @media print) -->
    <?php if (!empty($data['error'])): ?>
        <div class="no-print max-w-4xl mx-auto mb-4 rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs sm:text-sm text-amber-800 font-medium">
            <?= htmlspecialchars($data['error']) ?>
        </div>
    <?php endif; ?>

    <div class="no-print max-w-4xl mx-auto mb-6 bg-white border border-slate-200 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="/dashboard" class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 transition">
                &larr; Kembali ke Dasbor
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-900">Tahap 4: Pratinjau Cetak Rekapitulasi Nilai &amp; Surat Pernyataan</h1>
                <p class="text-xs text-slate-500">Cetak dokumen ini pada kertas A4, lalu lanjutkan ke Tahap 5 (Cetak Kartu Peserta).</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button
                type="button"
                onclick="window.print()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/20 transition"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Simpan PDF
            </button>
            <a href="/cetak/kartu" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                Lanjut Tahap 5: Cetak Kartu &rarr;
            </a>
        </div>
    </div>

    <!-- Lembar Dokumen Cetak A4 -->
    <div class="print-sheet max-w-4xl mx-auto bg-white text-black rounded-2xl shadow-lg p-4 sm:p-10 border border-slate-200 overflow-hidden">

        <!-- KOP SURAT RESMI SEKOLAH -->
        <div class="border-b-4 border-double border-black pb-3 mb-5 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-5 text-center">
            <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest">Pemerintah Provinsi Jawa Timur &mdash; Dinas Pendidikan</p>
                <h1 class="text-base sm:text-xl font-extrabold uppercase tracking-wide mt-0.5">
                    SMA Negeri 5 Taruna Brawijaya Jawa Timur
                </h1>
                <p class="text-[10px] sm:text-[11px] text-neutral-700">
                    Jl. Selomangleng No. 2, Kota Kediri, Jawa Timur &bull; Panitia Sistem Penerimaan Murid Baru (SPMB) Tahun <?= date('Y') ?>
                </p>
            </div>
        </div>

        <!-- JUDUL DOKUMEN -->
        <div class="text-center mb-5">
            <h2 class="text-xs sm:text-base font-extrabold uppercase underline">
                Rekapitulasi Nilai Rapor &amp; Surat Pernyataan Calon Taruna
            </h2>
            <p class="text-xs font-semibold mt-0.5">Nomor Peserta: <?= htmlspecialchars($nomorPeserta) ?></p>
        </div>

        <!-- IDENTITAS RINGKAS PENDAFTAR -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1.5 text-xs mb-5 border border-black p-3 sm:p-3.5 rounded">
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">Nama Lengkap</span>
                <span class="flex-1 min-w-0 break-words">: <strong><?= htmlspecialchars($bio['nama_lengkap'] ?? $user['nama_lengkap'] ?? '-') ?></strong></span>
            </div>
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">Jalur Pendaftaran</span>
                <span class="flex-1 min-w-0">: <strong><?= htmlspecialchars($user['jalur_pendaftaran'] ?? '-') ?></strong></span>
            </div>
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">NISN / NIK</span>
                <span class="flex-1 min-w-0 break-all">: <?= htmlspecialchars($user['nisn'] ?? '-') ?> / <?= htmlspecialchars($user['nik'] ?? '-') ?></span>
            </div>
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">Asal Sekolah</span>
                <span class="flex-1 min-w-0">: <?= htmlspecialchars($bio['nama_sekolah_asal'] ?? '-') ?> (Lulus: <?= htmlspecialchars((string)($bio['tahun_lulus'] ?? $user['tahun_lulus'] ?? '-')) ?>)</span>
            </div>
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">Tempat, Tgl Lahir</span>
                <span class="flex-1 min-w-0">: <?= htmlspecialchars($bio['tempat_lahir'] ?? '-') ?>, <?= !empty($bio['tanggal_lahir']) ? date('d-m-Y', strtotime($bio['tanggal_lahir'])) : '-' ?></span>
            </div>
            <div class="flex flex-wrap">
                <span class="w-32 sm:w-36 font-semibold shrink-0">No. HP / WhatsApp</span>
                <span class="flex-1 min-w-0">: <?= htmlspecialchars($user['no_hp'] ?? '-') ?></span>
            </div>
        </div>

        <!-- TABEL REKAPITULASI NILAI RAPOR SEMESTER 1 - 4 -->
        <div class="mb-6">
            <h3 class="text-xs font-bold uppercase mb-2">A. Tabel Rekapitulasi Nilai Rapor (Semester 1 s.d. Semester 4)</h3>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[540px] border-collapse border border-black text-xs">
                <thead>
                    <tr class="bg-neutral-100">
                        <th class="border border-black py-2 px-2 text-center w-10">No</th>
                        <th class="border border-black py-2 px-3 text-left">Mata Pelajaran</th>
                        <th class="border border-black py-2 px-2 text-center w-16">Sem 1</th>
                        <th class="border border-black py-2 px-2 text-center w-16">Sem 2</th>
                        <th class="border border-black py-2 px-2 text-center w-16">Sem 3</th>
                        <th class="border border-black py-2 px-2 text-center w-16">Sem 4</th>
                        <th class="border border-black py-2 px-2 text-center w-20">Rata-Rata</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($mapelList as $kodeMapel => $namaLengkapMapel): ?>
                        <tr>
                            <td class="border border-black py-1.5 px-2 text-center"><?= $no++ ?></td>
                            <td class="border border-black py-1.5 px-3 font-medium"><?= htmlspecialchars($namaLengkapMapel) ?></td>
                            <?php for ($sem = 1; $sem <= 4; $sem++): ?>
                                <?php $val = $nilaiMatrix[$sem][$kodeMapel] ?? null; ?>
                                <td class="border border-black py-1.5 px-2 text-center">
                                    <?= $val !== null ? number_format((float)$val, 2) : '-' ?>
                                </td>
                            <?php endfor; ?>
                            <td class="border border-black py-1.5 px-2 text-center font-bold bg-neutral-50">
                                <?= number_format((float)($rataPerMapel[$kodeMapel] ?? 0), 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-neutral-100 font-bold">
                        <td colspan="2" class="border border-black py-2 px-3 text-right">Jumlah Nilai per Semester</td>
                        <?php for ($sem = 1; $sem <= 4; $sem++): ?>
                            <td class="border border-black py-2 px-2 text-center">
                                <?= number_format((float)($totalPerSemester[$sem] ?? 0), 2) ?>
                            </td>
                        <?php endfor; ?>
                        <td class="border border-black py-2 px-2 text-center">
                            <?= number_format((float)$grandTotal, 2) ?>
                        </td>
                    </tr>
                    <tr class="bg-neutral-200 font-extrabold">
                        <td colspan="2" class="border border-black py-2 px-3 text-right">Rata-Rata Nilai per Semester</td>
                        <?php for ($sem = 1; $sem <= 4; $sem++): ?>
                            <td class="border border-black py-2 px-2 text-center">
                                <?= number_format((float)($rataPerSemester[$sem] ?? 0), 2) ?>
                            </td>
                        <?php endfor; ?>
                        <td class="border border-black py-2 px-2 text-center text-sm">
                            <?= number_format((float)$grandAverage, 2) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        <!-- SURAT PERNYATAAN CALON TARUNA & ORANG TUA -->
        <div class="border border-black p-3.5 sm:p-4 rounded text-xs space-y-3 leading-relaxed">
            <h3 class="text-xs font-extrabold uppercase text-center underline">
                Surat Pernyataan Kebenaran Data &amp; Nilai Rapor
            </h3>
            <p>
                Saya yang bertanda tangan di bawah ini, Calon Taruna SPMB SMAN 5 Taruna Brawijaya Jawa Timur:
            </p>
            <div class="pl-2 sm:pl-4 space-y-1">
                <p>Nama Lengkap : <strong><?= htmlspecialchars($bio['nama_lengkap'] ?? $user['nama_lengkap'] ?? '-') ?></strong></p>
                <p>NISN / Nomor Peserta : <strong><?= htmlspecialchars($user['nisn'] ?? '-') ?> / <?= htmlspecialchars($nomorPeserta) ?></strong></p>
                <p>Nama Orang Tua / Wali : <strong><?= htmlspecialchars($bio['nama_ayah'] ?? $bio['nama_ibu'] ?? '-') ?></strong></p>
            </div>
            <p class="text-justify">
                Dengan ini menyatakan dengan sesungguhnya bahwa seluruh data biodata, dokumen persyaratan, serta isian nilai rapor Semester 1 sampai dengan Semester 4 yang saya inputkan pada Sistem Penerimaan Murid Baru (SPMB) SMAN 5 Taruna Brawijaya adalah <strong>benar dan sesuai dengan dokumen rapor asli</strong>. Apabila di kemudian hari ditemukan ketidaksesuaian atau pemalsuan data, maka saya bersedia menerima sanksi pembatalan kelulusan seleksi sesuai ketentuan yang berlaku.
            </p>

            <!-- Kolom Tanda Tangan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 text-center">
                <div>
                    <p>Mengetahui &amp; Menyetujui,</p>
                    <p class="font-semibold">Orang Tua / Wali Calon Taruna</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline">
                        ( <?= htmlspecialchars($bio['nama_ayah'] ?: ($bio['nama_ibu'] ?? '........................................')) ?> )
                    </p>
                </div>
                <div>
                    <p><?= htmlspecialchars($bio['kabupaten_kota'] ?? 'Kediri') ?>, <?= date('d F Y') ?></p>
                    <p class="font-semibold">Calon Taruna Pendaftar</p>
                    <div class="h-20 flex items-center justify-center">
                        <span class="border border-dashed border-neutral-500 px-3 py-2 text-[10px] text-neutral-600">
                            Meterai Rp 10.000
                        </span>
                    </div>
                    <p class="font-bold underline">
                        ( <?= htmlspecialchars($bio['nama_lengkap'] ?? $user['nama_lengkap'] ?? '........................................') ?> )
                    </p>
                </div>
            </div>
        </div>

        <p class="mt-3 text-[10px] text-neutral-500 text-right">
            Dicetak melalui Portal SPMB SMAN 5 Taruna Brawijaya pada <?= date('d/m/Y H:i') ?> WIB
        </p>
    </div>

</body>
</html>

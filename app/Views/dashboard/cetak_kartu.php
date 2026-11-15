<!DOCTYPE html>
<html lang="id">
<head>
    <?php
    $data['meta_description'] = 'Cetak Kartu Tanda Peserta Seleksi SPMB SMAN 5 Taruna Brawijaya Jawa Timur.';
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
                border: 2px solid #000000 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen py-8 px-4 sm:px-6">

    <?php
    $user         = $data['user'] ?? [];
    $bio          = $data['biodata'] ?? [];
    $berkas       = $data['berkas'] ?? [];
    $nomorPeserta = $data['nomor_peserta'] ?? '-';
    $isBeasiswa   = (stripos($user['jalur_pendaftaran'] ?? '', 'Beasiswa') !== false);
    $jmlSertifikat = isset($berkas['sertifikat']) && is_array($berkas['sertifikat']) ? count($berkas['sertifikat']) : 0;
    ?>

    <!-- Action Bar (Disembunyikan saat mode cetak kertas / @media print) -->
    <div class="no-print max-w-3xl mx-auto mb-6 bg-white border border-slate-200 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="/dashboard" class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 transition">
                &larr; Kembali ke Dasbor
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-900">Pratinjau Cetak Kartu Peserta SPMB</h1>
                <p class="text-xs text-slate-500">Cetak kartu ini pada kertas A4 dan bawa saat verifikasi berkas fisik &amp; pelaksanaan tes.</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="/cetak/rekap" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                &larr; Cetak Rekap Nilai
            </a>
            <button
                type="button"
                onclick="window.print()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-600/20 transition"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Kartu Peserta
            </button>
        </div>
    </div>

    <!-- KARTU PESERTA CETAK -->
    <div class="print-sheet max-w-3xl mx-auto bg-white text-black rounded-2xl shadow-lg p-4 sm:p-8 border-2 border-black overflow-hidden">

        <!-- KOP KARTU PESERTA -->
        <div class="flex flex-col sm:flex-row items-center justify-between border-b-4 border-double border-black pb-4 mb-5 gap-3 text-center sm:text-left">
            <img src="/assets/img/logo-smata-150x150.png" alt="Logo SMAN 5 Taruna Brawijaya" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0">
            <div class="text-center px-2 sm:px-4 flex-1">
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-widest">Panitia Seleksi Penerimaan Murid Baru (SPMB)</p>
                <h1 class="text-base sm:text-lg font-extrabold uppercase tracking-wide">
                    SMA Negeri 5 Taruna Brawijaya Jawa Timur
                </h1>
                <p class="text-[10px] sm:text-[11px] text-neutral-700">Jl. Selomangleng No. 2, Kota Kediri, Jawa Timur</p>
            </div>
            <div class="text-center sm:text-right shrink-0 border-2 border-black px-3 py-1.5 rounded-lg">
                <span class="text-[10px] font-bold uppercase block text-neutral-600">Tahun Ajaran</span>
                <span class="text-xs sm:text-sm font-extrabold"><?= date('Y') ?>/<?= date('Y') + 1 ?></span>
            </div>
        </div>

        <!-- HEADER NOMOR PESERTA & JALUR -->
        <div class="bg-neutral-100 border-2 border-black rounded-xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 block">Nomor Peserta Seleksi</span>
                <span class="text-xl font-extrabold tracking-wider"><?= htmlspecialchars($nomorPeserta) ?></span>
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 block">Jalur Pendaftaran</span>
                <span class="inline-block mt-0.5 px-3 py-1 rounded border border-black font-extrabold text-xs uppercase bg-white">
                    <?= htmlspecialchars($user['jalur_pendaftaran'] ?? '-') ?>
                </span>
            </div>
        </div>

        <!-- ISI KARTU: DATA PESERTA & KOTAK PAS FOTO 3x4 -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-6">
            <!-- Tabel Data Diri Peserta -->
            <div class="sm:col-span-3">
                <h2 class="text-xs font-extrabold uppercase tracking-wider border-b border-black pb-1.5 mb-3">
                    A. Identitas Calon Taruna
                </h2>
                <table class="w-full text-xs">
                    <tbody class="divide-y divide-neutral-200">
                        <tr>
                            <td class="py-2 font-semibold w-40">Nama Lengkap</td>
                            <td class="py-2 font-extrabold uppercase">: <?= htmlspecialchars($bio['nama_lengkap'] ?? $user['nama_lengkap'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">NISN / NIK</td>
                            <td class="py-2">: <?= htmlspecialchars($user['nisn'] ?? '-') ?> / <?= htmlspecialchars($user['nik'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Tempat, Tanggal Lahir</td>
                            <td class="py-2">: <?= htmlspecialchars($bio['tempat_lahir'] ?? '-') ?>, <?= !empty($bio['tanggal_lahir']) ? date('d-m-Y', strtotime($bio['tanggal_lahir'])) : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Jenis Kelamin / Agama</td>
                            <td class="py-2">: <?= (($bio['jenis_kelamin'] ?? '') === 'L') ? 'Laki-laki' : ((($bio['jenis_kelamin'] ?? '') === 'P') ? 'Perempuan' : '-') ?> / <?= htmlspecialchars($bio['agama'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Asal Sekolah (SMP/MTs)</td>
                            <td class="py-2 font-bold">: <?= htmlspecialchars($bio['nama_sekolah_asal'] ?? '-') ?> <?= !empty($bio['npsn_sekolah_asal']) ? '(NPSN: ' . htmlspecialchars($bio['npsn_sekolah_asal']) . ')' : '' ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Tahun Lulus</td>
                            <td class="py-2">: <?= htmlspecialchars((string)($bio['tahun_lulus'] ?? $user['tahun_lulus'] ?? '-')) ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">Alamat Domisili</td>
                            <td class="py-2">: <?= htmlspecialchars($bio['alamat_lengkap'] ?? '-') ?>, <?= htmlspecialchars($bio['kelurahan_desa'] ?? '') ?>, <?= htmlspecialchars($bio['kecamatan'] ?? '') ?>, <?= htmlspecialchars($bio['kabupaten_kota'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-semibold">No. HP Peserta / Ortu</td>
                            <td class="py-2">: <?= htmlspecialchars($user['no_hp'] ?? '-') ?> / <?= htmlspecialchars($bio['no_hp_ortu'] ?? '-') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Kotak Pas Foto 3x4 -->
            <div class="sm:col-span-1 flex flex-col items-center justify-start">
                <div class="w-32 h-44 border-2 <?= !empty($bio['pas_foto']) ? 'border-black' : 'border-dashed border-black' ?> rounded overflow-hidden flex flex-col items-center justify-center text-center bg-neutral-50">
                    <?php if (!empty($bio['pas_foto'])): ?>
                        <img src="<?= htmlspecialchars($bio['pas_foto']) ?>" alt="Pas Foto Peserta" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="p-3">
                            <span class="text-xs font-bold uppercase block">Pas Foto</span>
                            <span class="text-sm font-extrabold mt-1 block">3 &times; 4 cm</span>
                            <span class="text-[10px] text-neutral-600 mt-2 block leading-tight">
                                Tempel pas foto berwarna terbaru berseragam sekolah asal
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- DAFTAR CHECKLIST BERKAS FISIK VERIFIKASI -->
        <div class="mb-6">
            <h2 class="text-xs font-extrabold uppercase tracking-wider border-b border-black pb-1.5 mb-2.5">
                B. Daftar Checklist Verifikasi Berkas Fisik (Diisi oleh Panitia Verifikator)
            </h2>
            <p class="text-[11px] text-neutral-700 mb-2">
                Bawa dokumen asli beserta 1 rangkap fotokopi legalisir di dalam map sesuai ketentuan saat pelaksanaan verifikasi berkas fisik:
            </p>
            <div class="overflow-x-auto">
            <table class="w-full min-w-[500px] border-collapse border border-black text-xs">
                <thead>
                    <tr class="bg-neutral-100">
                        <th class="border border-black py-1.5 px-2 text-center w-10">No</th>
                        <th class="border border-black py-1.5 px-3 text-left">Nama Dokumen / Berkas Persyaratan</th>
                        <th class="border border-black py-1.5 px-2 text-center w-28">Status Unggah</th>
                        <th class="border border-black py-1.5 px-2 text-center w-24">Cek Fisik (Ada)</th>
                        <th class="border border-black py-1.5 px-2 text-center w-28">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $checklistItems = [
                        ['label' => 'Kartu Peserta & Rekapitulasi Nilai + Surat Pernyataan (Bermeterai)', 'uploaded' => true],
                        ['label' => 'Kartu Keluarga (KK) Asli & Fotokopi', 'uploaded' => !empty($berkas['kk'])],
                        ['label' => 'Surat Keterangan Sehat dari Puskesmas / RS Pemerintah', 'uploaded' => !empty($berkas['keterangan_sehat'])],
                        ['label' => 'Surat Keterangan Kelakuan Baik dari Sekolah Asal / SKCK', 'uploaded' => !empty($berkas['kelakuan_baik'])],
                        ['label' => 'Ijazah / Surat Keterangan Lulus (SKL) Asli & Fotokopi Legalisir', 'uploaded' => !empty($berkas['ijazah'])],
                        ['label' => 'Rapor Asli SMP/MTs (Semester 1 s.d. Semester 4) & Fotokopi Legalisir', 'uploaded' => true],
                        [
                            'label'    => 'Sertifikat / Piagam Prestasi Asli & Fotokopi (' . ($isBeasiswa ? 'Wajib Jalur Beasiswa' : 'Opsional Jalur Umum') . ')',
                            'uploaded' => $jmlSertifikat > 0,
                            'extra'    => $jmlSertifikat > 0 ? "{$jmlSertifikat} File" : 'Belum Ada'
                        ]
                    ];
                    ?>
                    <?php foreach ($checklistItems as $idx => $item): ?>
                        <tr>
                            <td class="border border-black py-1.5 px-2 text-center"><?= $idx + 1 ?></td>
                            <td class="border border-black py-1.5 px-3 font-medium"><?= htmlspecialchars($item['label']) ?></td>
                            <td class="border border-black py-1.5 px-2 text-center font-semibold">
                                <?php if (isset($item['extra'])): ?>
                                    <?= htmlspecialchars($item['extra']) ?>
                                <?php else: ?>
                                    <?= $item['uploaded'] ? 'Sudah Unggah' : 'Belum Unggah' ?>
                                <?php endif; ?>
                            </td>
                            <td class="border border-black py-1.5 px-2 text-center">
                                <span class="inline-block w-4 h-4 border border-black"></span>
                            </td>
                            <td class="border border-black py-1.5 px-2"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- PENGESAHAN KARTU PESERTA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 text-xs text-center">
            <div>
                <p class="font-semibold">Petugas Verifikator SPMB</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( .................................................... )</p>
                <p class="text-[10px] text-neutral-600">NIP / Panitia Seleksi</p>
            </div>
            <div>
                <p>Kediri, <?= date('d F Y') ?></p>
                <p class="font-semibold">Tanda Tangan Peserta</p>
                <div class="h-16"></div>
                <p class="font-bold underline">
                    ( <?= htmlspecialchars($bio['nama_lengkap'] ?? $user['nama_lengkap'] ?? '........................................') ?> )
                </p>
                <p class="text-[10px] text-neutral-600">NISN: <?= htmlspecialchars($user['nisn'] ?? '-') ?></p>
            </div>
        </div>

        <div class="mt-4 pt-2 border-t border-dashed border-neutral-400 flex items-center justify-between text-[10px] text-neutral-600">
            <span>Harap kartu ini disimpan dengan baik dan tidak dilipat.</span>
            <span>Dicetak: <?= date('d/m/Y H:i') ?> WIB</span>
        </div>
    </div>

</body>
</html>

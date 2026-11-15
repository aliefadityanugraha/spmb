<?php
/**
 * Komponen Modular SEO & Structured Data (JSON-LD)
 * Digunakan di seluruh halaman SPMB SMAN 5 Taruna Brawijaya Jawa Timur
 */
$scheme      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host        = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
$baseUrl     = $scheme . '://' . $host;
$requestUri  = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$canonical   = $baseUrl . ($requestUri ?: '/');

$seoTitle       = $data['title'] ?? 'SPMB SMAN 5 Taruna Brawijaya Jawa Timur 2027/2028 — Portal Resmi Penerimaan Calon Taruna';
$seoDescription = $data['meta_description'] ?? 'Portal Resmi Seleksi Penerimaan Murid Baru (SPMB) SMAN 5 Taruna Brawijaya Jawa Timur Tahun Ajaran 2027/2028. Kuota 270 Taruna/Taruni melalui Jalur Umum dan Jalur Beasiswa Prestasi.';
$seoKeywords    = $data['meta_keywords'] ?? 'SPMB SMAN 5 Taruna Brawijaya, PPDB SMAN 5 Taruna Brawijaya, SMA Taruna Brawijaya Kediri, Pendaftaran Taruna Brawijaya Jawa Timur, Seleksi Penerimaan Murid Baru, Jalur Beasiswa Taruna, Jalur Umum Taruna, SMAN 5 Kediri';
$seoRobots      = $data['meta_robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$seoImage       = $baseUrl . ($data['meta_image'] ?? '/assets/img/pentab.png');
$seoLogo        = $baseUrl . '/assets/img/logo-smata-150x150.png';
$isHomePage     = ($requestUri === '/' || $requestUri === '' || $requestUri === '/home');
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Primary SEO Meta Tags -->
    <title><?= htmlspecialchars($seoTitle) ?></title>
    <meta name="title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($seoDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seoKeywords) ?>">
    <meta name="author" content="Panitia SPMB SMAN 5 Taruna Brawijaya Jawa Timur">
    <meta name="robots" content="<?= htmlspecialchars($seoRobots) ?>">
    <meta name="googlebot" content="<?= htmlspecialchars($seoRobots) ?>">
    <meta name="theme-color" content="#059669">

    <!-- Canonical & Sitemap Links -->
    <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
    <link rel="sitemap" type="application/xml" title="Sitemap SPMB SMAN 5 Taruna Brawijaya" href="<?= htmlspecialchars($baseUrl . '/sitemap.xml') ?>">

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/png" sizes="150x150" href="/assets/img/logo-smata-150x150.png">
    <link rel="apple-touch-icon" href="/assets/img/logo-smata-150x150.png">

    <!-- Resource Hints (Preconnect & DNS-Prefetch untuk mempercepat muat aset CDN) -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="SPMB SMAN 5 Taruna Brawijaya Jawa Timur">
    <meta property="og:url" content="<?= htmlspecialchars($canonical) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seoImage) ?>">
    <meta property="og:image:alt" content="Logo SPMB SMAN 5 Taruna Brawijaya Jawa Timur">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= htmlspecialchars($canonical) ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($seoImage) ?>">

    <meta name="google-site-verification" content="b8eL23mPx5FvRPh6YvSP7DGfhEkc2Rofm2x2lhK1MBQ" />

    <!-- JSON-LD Structured Data: EducationalOrganization & WebSite -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "EducationalOrganization",
                "@id": "<?= htmlspecialchars($baseUrl) ?>/#organization",
                "name": "SMAN 5 Taruna Brawijaya Jawa Timur",
                "alternateName": "SMA Negeri 5 Taruna Brawijaya Kediri",
                "url": "<?= htmlspecialchars($baseUrl) ?>/",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= htmlspecialchars($seoLogo) ?>"
                },
                "image": "<?= htmlspecialchars($seoImage) ?>",
                "description": "<?= htmlspecialchars($seoDescription) ?>",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Jl. Selomangleng No. 2, Sukorame, Kec. Mojoroto",
                    "addressLocality": "Kota Kediri",
                    "addressRegion": "Jawa Timur",
                    "postalCode": "64114",
                    "addressCountry": "ID"
                }
            },
            {
                "@type": "WebSite",
                "@id": "<?= htmlspecialchars($baseUrl) ?>/#website",
                "url": "<?= htmlspecialchars($baseUrl) ?>/",
                "name": "SPMB SMAN 5 Taruna Brawijaya Jawa Timur",
                "description": "Portal Resmi Sistem Penerimaan Murid Baru (SPMB) SMAN 5 Taruna Brawijaya Jawa Timur",
                "publisher": {
                    "@id": "<?= htmlspecialchars($baseUrl) ?>/#organization"
                },
                "inLanguage": "id-ID"
            }
        ]
    }
    </script>
<?php if ($isHomePage): ?>
    <!-- JSON-LD Structured Data: FAQPage untuk Rich Snippet Google -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Apa saja syarat utama pendaftaran SPMB SMAN 5 Taruna Brawijaya 2027/2028?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "1) Warga Negara Indonesia usia 14 s.d. 17 tahun saat awal Tahun Ajaran 2027/2028; 2) Murid SMP/MTs/Sederajat Kelas IX Tahun Ajaran 2026/2027 atau murid lulusan tahun sebelumnya; 3) Tinggi badan minimal 155 cm untuk Laki-Laki dan minimal 150 cm untuk Perempuan dengan berat badan proporsional; 4) Nilai rata-rata rapor Semester 1, 2, 3, dan 4 minimal 80; 5) Sehat jasmani dan rohani."
                }
            },
            {
                "@type": "Question",
                "name": "Kapan jadwal pendaftaran dan tahapan seleksi SPMB SMAN 5 Taruna Brawijaya 2027/2028?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Pendaftaran Online/Seleksi Tahap I: 16 November s.d. 26 Desember 2026; Pengumuman Tahap I: 29 Desember 2026; Seleksi Tahap II: 4-10 Januari 2027; Pantukhir: 11 Januari 2027; Pengumuman Akhir: 19 Januari 2027; Daftar Ulang: 19-22 Januari 2027."
                }
            },
            {
                "@type": "Question",
                "name": "Berapa kuota penerimaan dan nomor kontak resmi Panitia SPMB SMAN 5 Taruna Brawijaya?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Kuota penerimaan SPMB Tahun Ajaran 2027/2028 adalah 270 Taruna/Taruni melalui Jalur Umum dan Jalur Beasiswa Prestasi. Informasi lebih lanjut dapat diakses melalui spmb.smatarunakediri.sch.id atau WhatsApp 0815-7788-995."
                }
            }
        ]
    }
    </script>
<?php endif; ?>

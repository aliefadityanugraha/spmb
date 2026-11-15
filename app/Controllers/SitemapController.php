<?php
class SitemapController extends Controller {
    /**
     * Menghasilkan XML Sitemap dinamis untuk mesin pencari (Google, Bing, dll.)
     * Dapat diakses melalui /sitemap.xml atau /sitemap
     */
    public function index() {
        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex');

        $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host    = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
        $baseUrl = $scheme . '://' . $host;
        $today   = date('Y-m-d');

        $urls = [
            [
                'loc'        => $baseUrl . '/',
                'lastmod'    => $today,
                'changefreq' => 'daily',
                'priority'   => '1.0',
                'image'      => $baseUrl . '/assets/img/pentab.png',
                'image_title'=> 'Portal Resmi SPMB SMAN 5 Taruna Brawijaya Jawa Timur'
            ],
            [
                'loc'        => $baseUrl . '/auth/register',
                'lastmod'    => $today,
                'changefreq' => 'weekly',
                'priority'   => '0.9',
                'image'      => $baseUrl . '/assets/img/logo-smata-150x150.png',
                'image_title'=> 'Registrasi Akun Calon Taruna SMAN 5 Taruna Brawijaya'
            ],
            [
                'loc'        => $baseUrl . '/auth/login',
                'lastmod'    => $today,
                'changefreq' => 'weekly',
                'priority'   => '0.8',
                'image'      => $baseUrl . '/assets/img/logo-smata-150x150.png',
                'image_title'=> 'Login Portal SPMB SMAN 5 Taruna Brawijaya'
            ]
        ];

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            echo "    <lastmod>" . htmlspecialchars($u['lastmod'], ENT_XML1, 'UTF-8') . "</lastmod>\n";
            echo "    <changefreq>" . htmlspecialchars($u['changefreq'], ENT_XML1, 'UTF-8') . "</changefreq>\n";
            echo "    <priority>" . htmlspecialchars($u['priority'], ENT_XML1, 'UTF-8') . "</priority>\n";
            if (!empty($u['image'])) {
                echo "    <image:image>\n";
                echo "      <image:loc>" . htmlspecialchars($u['image'], ENT_XML1, 'UTF-8') . "</image:loc>\n";
                echo "      <image:title>" . htmlspecialchars($u['image_title'], ENT_XML1, 'UTF-8') . "</image:title>\n";
                echo "    </image:image>\n";
            }
            echo "  </url>\n";
        }

        echo "</urlset>\n";
        exit;
    }
}

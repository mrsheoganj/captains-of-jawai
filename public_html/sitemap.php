<?php
header("Content-Type: text/xml;charset=iso-8859-1");

// Determine the base URL dynamically based on the current domain
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$domain = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $domain;

$pages = [
    '/',
    '/about.php',
    '/plan-your-journey/',
    '/contact.php'
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $page) {
    echo "  <url>\n";
    echo "      <loc>" . $base_url . $page . "</loc>\n";
    // Set changefreq and priority based on page
    if ($page === '/') {
        echo "      <changefreq>daily</changefreq>\n";
        echo "      <priority>1.0</priority>\n";
    } else {
        echo "      <changefreq>weekly</changefreq>\n";
        echo "      <priority>0.8</priority>\n";
    }
    echo "  </url>\n";
}

echo '</urlset>';
?>

<?php
/**
 * Sitemap XML généré depuis le contenu réel (pages, logements, actualités).
 */
require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/functions.php';

$base = 'https://gelpaz.com';
$today = date('Y-m-d');

$urls = [
    ['loc' => '/', 'priority' => '1.0'],
    ['loc' => '/nous-connaitre', 'priority' => '0.7'],
    ['loc' => '/nos-activites', 'priority' => '0.8'],
    ['loc' => '/nos-offres-immobilieres', 'priority' => '0.9'],
    ['loc' => '/souscription-logement', 'priority' => '0.7'],
    ['loc' => '/blog-list-no-sidebar-2', 'priority' => '0.7'],
    ['loc' => '/team', 'priority' => '0.5'],
    ['loc' => '/faq', 'priority' => '0.5'],
    ['loc' => '/contact-us', 'priority' => '0.8'],
    ['loc' => '/mentions-legales', 'priority' => '0.2'],
];
foreach ($properties as $property) {
    $urls[] = ['loc' => '/estate_property/' . $property['id'], 'priority' => '0.9'];
}
foreach ($posts as $post) {
    $urls[] = ['loc' => '/article/' . $post['slug'], 'priority' => '0.6'];
}

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?= htmlspecialchars($base . $url['loc'], ENT_XML1, 'UTF-8') ?></loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq><?= $url['priority'] === '0.9' || $url['priority'] === '1.0' ? 'weekly' : 'monthly' ?></changefreq>
        <priority><?= $url['priority'] ?></priority>
    </url>
<?php endforeach; ?>
</urlset>

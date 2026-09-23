<?php
require __DIR__ . '/includes/bootstrap.php';
$canonical = 'https://gelpaz.com' . page_url($current_page, $current_page === 'property' ? ['id' => $selected_property['id']] : []);
$og_image = 'https://gelpaz.com' . img_url($current_page === 'property' ? $selected_property['image'] : $images['hero']);
?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(meta_description($current_page)) ?>">
    <meta name="theme-color" content="#0876d1">
    <title><?= e(page_title($current_page)) ?> · GELPAZ IMMO</title>
<?php if ($current_page !== '404'): ?>
    <link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon-180.png">
    <meta property="og:type" content="<?= $current_page === 'post' ? 'article' : 'website' ?>">
    <meta property="og:site_name" content="GELPAZ IMMO">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="<?= e(page_title($current_page)) ?> · GELPAZ IMMO">
    <meta property="og:description" content="<?= e(meta_description($current_page)) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($og_image) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "RealEstateAgent",
        "name": "GELPAZ IMMO",
        "url": "https://gelpaz.com",
        "email": <?= json_encode($site['email'], JSON_UNESCAPED_UNICODE) ?>,
        "telephone": <?= json_encode($site['phone'], JSON_UNESCAPED_UNICODE) ?>,
        "address": {"@type": "PostalAddress", "streetAddress": <?= json_encode($site['address'], JSON_UNESCAPED_UNICODE) ?>, "addressCountry": "BF"},
        "areaServed": "Ouagadougou, Burkina Faso",
        "openingHours": "Mo-Fr 08:00-17:00"
    }
    </script>
</head>
<body class="page-<?= e($current_page) ?>">
<?php render_icon_sprite(); ?>
<a class="skip-link" href="#main">Aller au contenu principal</a>
<?php require __DIR__ . '/pages/' . $current_page . '.php'; ?>
<?php require __DIR__ . '/pages/gallery-lightbox.php'; ?>
<?php render_footer(); ?>
<script src="/assets/js/app.js"></script>
</body>
</html>

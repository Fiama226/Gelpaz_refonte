<?php
require __DIR__ . '/includes/bootstrap.php';
?><!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(meta_description($current_page)) ?>">
    <meta name="theme-color" content="#0876d1">
    <title><?= e(page_title($current_page)) ?> · GELPAZ IMMO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="page-<?= e($current_page) ?>">
<?php require __DIR__ . '/pages/' . $current_page . '.php'; ?>
<?php render_footer(); ?>
<script src="/assets/js/app.js"></script>
</body>
</html>

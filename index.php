<?php
require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/layout.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$route_map = [
    'nous-connaitre' => 'about',
    'mission-vision-valeur' => 'about',
    'nos-offres-immobilieres' => 'properties',
    'properties-list-2' => 'properties',
    'nos-sites' => 'properties',
    'property_action_category/vente' => 'properties',
    'property_area/centre' => 'properties',
    'souscription-logement' => 'pricing',
    'nos-activites' => 'services',
    'blog-list-no-sidebar-2' => 'blog',
    'contact-us' => 'contact',
    'nos-realisations' => 'properties',
    'team' => 'team',
    'faq' => 'faq',
    'pricing' => 'pricing',
];
$route_page = $route_map[$path] ?? null;
if (str_starts_with($path, 'estate_property/')) {
    $route_page = 'property';
    $_GET['id'] = basename($path);
}
if ($route_page === 'properties' && $path === 'property_action_category/vente') {
    $_GET['filter'] = 'vente';
}
$page = $_GET['page'] ?? $route_page ?? 'home';
$allowed_pages = ['home', 'about', 'services', 'properties', 'property', 'blog', 'post', 'contact', 'team', 'faq', 'pricing'];
$current_page = in_array($page, $allowed_pages, true) ? $page : 'home';
$GLOBALS['current_page'] = $current_page;

$property_id = $_GET['id'] ?? 'modele-f4c';
$selected_property = property_by_id($property_id) ?: $properties[0];
$property_filter = strtolower(trim((string) ($_GET['filter'] ?? 'all')));
$visible_properties = array_values(array_filter($properties, static function (array $property) use ($property_filter): bool {
    return $property_filter === 'all' || strtolower($property['category']) === $property_filter;
}));
if ($visible_properties === []) {
    $visible_properties = $properties;
    $property_filter = 'all';
}
$contact_success = $current_page === 'contact'
    && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

function meta_description(string $page): string
{
    $descriptions = [
        'home' => 'GELPAZ IMMO, votre partenaire immobilier au Burkina Faso. Découvrez nos villas, nos offres et notre accompagnement personnalisé.',
        'about' => 'Découvrez GELPAZ IMMO, son histoire, ses valeurs et sa vision pour un habitat de qualité au Burkina Faso.',
        'services' => 'Vente, location, gestion et conseil immobilier : GELPAZ IMMO vous accompagne dans tous vos projets.',
        'properties' => 'Découvrez les logements et villas proposés par GELPAZ IMMO à Ouagadougou et au Burkina Faso.',
        'property' => 'Découvrez les détails de cette propriété GELPAZ IMMO.',
        'blog' => 'Les actualités et conseils immobiliers de GELPAZ IMMO.',
        'post' => 'Actualité immobilière et conseils GELPAZ IMMO.',
        'contact' => 'Contactez GELPAZ IMMO à Ouagadougou pour votre projet immobilier.',
    ];
    return $descriptions[$page] ?? $descriptions['home'];
}
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="page-<?= e($current_page) ?>">
<?php if ($current_page === 'home'): ?>
    <main>
        <section class="home-hero" style="--hero-image: url('<?= img_url($images['hero_alt']) ?>')">
            <?php render_header(true); ?>
            <div class="home-hero__shade"></div>
            <div class="container home-hero__content">
                <p class="eyebrow eyebrow--light">VOTRE PARTENAIRE IMMOBILIER AU BURKINA FASO</p>
                <h1>La différence,<br><em>c’est notre</em> engagement.</h1>
                <p class="home-hero__copy">Des solutions immobilières pensées pour vous, un accompagnement qui fait toute la différence.</p>
                <div class="button-row">
                    <a class="button button--accent" href="<?= page_url('properties') ?>">Découvrir nos logements <span>↗</span></a>
                    <a class="button button--outline-light" href="<?= page_url('contact') ?>">Parler à un conseiller</a>
                </div>
                <div class="home-hero__proof">
                    <span class="avatar-stack"><i>G</i><i>I</i><i>M</i></span>
                    <span><b>+30 ans</b><small>d’expérience immobilière</small></span>
                    <span class="proof-line"></span>
                    <span><b>100%</b><small>d’écoute &amp; d’engagement</small></span>
                </div>
            </div>
            <a class="scroll-cue" href="#intro"><span>↓</span> Découvrir</a>
        </section>

        <section class="section intro-section" id="intro">
            <div class="container split-section">
                <div class="split-section__media media-frame">
                    <img src="<?= img_url($images['hero']) ?>" alt="Une maison proposée par Gelpaz Immo" loading="lazy">
                    <span class="media-frame__caption">L’immobilier avec une vision humaine</span>
                </div>
                <div class="split-section__content">
                    <p class="eyebrow">GELPAZ IMMO · LA DIFFÉRENCE</p>
                    <h2>Créer de la valeur,<br><em>habiter mieux.</em></h2>
                    <p class="lead">Depuis plus de 30 ans, GELPAZ IMMO accompagne les familles et les investisseurs dans la concrétisation de leurs projets immobiliers au Burkina Faso.</p>
                    <p>Nous plaçons l’écoute, la transparence et le professionnalisme au cœur de chaque relation pour vous proposer une expérience simple, sereine et adaptée à vos attentes.</p>
                    <a class="text-link text-link--dark" href="<?= page_url('about') ?>">Découvrir notre histoire <span>↗</span></a>
                    <div class="stats-row">
                        <div><strong>30<sup>+</sup></strong><span>ans d’expérience</span></div>
                        <div><strong>6</strong><span>offres à découvrir</span></div>
                        <div><strong>1</strong><span>objectif : votre satisfaction</span></div>
                    </div>
                </div>
            </div>
            <div class="container logo-strip">
                <span>Ils nous font confiance</span>
                <b>GELPAZ</b><b>IMMO</b><b>BURKINA</b><b>PARTENAIRES</b><b>PROJETS</b>
            </div>
        </section>

        <section class="section section--dark featured-section">
            <div class="container">
                <?php render_section_heading('NOS LOGEMENTS', 'Des espaces qui vous <em>ressemblent.</em>', 'Découvrez une sélection de logements de qualité, pensés pour votre quotidien.', 'center'); ?>
                <div class="featured-grid">
                    <?php foreach (array_slice($properties, 0, 3) as $property): ?>
                        <article class="featured-property">
                            <a href="<?= page_url('property', ['id' => $property['id']]) ?>" class="featured-property__image">
                                <img src="<?= img_url($property['image']) ?>" alt="<?= e($property['title']) ?>" loading="lazy">
                                <span class="play-dot">↗</span>
                            </a>
                            <div class="featured-property__info">
                                <div><p><?= e($property['location']) ?></p><h3><?= e($property['title']) ?></h3></div>
                                <strong><?= e($property['price']) ?></strong>
                            </div>
                            <div class="featured-property__meta"><span><?= e($property['beds']) ?> chambres</span><span><?= e($property['baths']) ?> salles d’eau</span><span><?= e($property['area']) ?></span></div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="center-action"><a class="button button--light" href="<?= page_url('properties') ?>">Voir tous nos logements <span>↗</span></a></div>
            </div>
        </section>

        <section class="section services-preview">
            <div class="container">
                <?php render_section_heading('NOTRE SAVOIR-FAIRE', 'Des solutions immobilières <em>complètes.</em>', 'De la recherche à la réalisation, nous sommes à vos côtés.', 'center'); ?>
                <div class="service-cards">
                    <?php foreach ($services as $service): ?>
                        <article class="service-card"><span class="service-card__icon"><?= e($service['icon']) ?></span><h3><?= e($service['title']) ?></h3><p><?= e($service['text']) ?></p><a href="<?= page_url('services') ?>" aria-label="En savoir plus sur <?= e($service['title']) ?>">↗</a></article>
                    <?php endforeach; ?>
                </div>
                <div class="process-feature">
                    <div class="process-feature__image"><img src="<?= img_url($images['villa']) ?>" alt="Une villa Gelpaz Immo" loading="lazy"></div>
                    <div class="process-feature__content"><p class="eyebrow">SIMPLE, CLAIR, HUMAIN</p><h2>Votre projet,<br><em>notre accompagnement.</em></h2><p>Choisissez votre propriété, échangez avec notre équipe et avancez sereinement vers la réalisation de votre projet.</p><a class="button button--dark" href="<?= page_url('services') ?>">Comment ça marche ? <span>↗</span></a></div>
                </div>
                <div class="steps-row">
                    <div><span>01</span><h3>Choisir</h3><p>Explorez les biens qui correspondent à votre projet.</p></div>
                    <div><span>02</span><h3>Échanger</h3><p>Notre équipe répond à vos questions et vous conseille.</p></div>
                    <div><span>03</span><h3>Réserver</h3><p>Validez votre choix avec un accompagnement clair.</p></div>
                    <div><span>04</span><h3>Concrétiser</h3><p>Donnez vie à votre projet en toute confiance.</p></div>
                </div>
            </div>
        </section>

        <section class="section team-preview section--soft">
            <div class="container">
                <?php render_section_heading('UNE ÉQUIPE À VOS CÔTÉS', 'Des experts derrière chaque <em>projet.</em>', 'Une équipe engagée pour vous apporter les bons conseils au bon moment.', 'center'); ?>
                <div class="team-grid">
                    <?php foreach ($team as $member): ?><article class="team-card"><img src="<?= img_url($member['image']) ?>" alt="<?= e($member['name']) ?>" loading="lazy"><h3><?= e($member['name']) ?></h3><p><?= e($member['role']) ?></p></article><?php endforeach; ?>
                </div>
                <div class="center-action"><a class="text-link text-link--dark" href="<?= page_url('about') ?>">Faire connaissance avec GELPAZ <span>↗</span></a></div>
            </div>
        </section>

        <section class="section testimonials-section">
            <div class="container testimonial-layout">
                <div class="testimonial-copy"><p class="eyebrow">ILS NOUS FONT CONFIANCE</p><h2>Écoutez ceux qui ont choisi <em>Gelpaz.</em></h2><p>Chaque projet est unique. Les histoires de nos clients sont la meilleure preuve de notre engagement.</p><a class="button button--dark" href="<?= page_url('contact') ?>">Parlons de votre projet <span>↗</span></a></div>
                <div class="testimonial-list">
                    <?php foreach ($testimonials as $testimonial): ?><article class="testimonial"><span class="quote-mark">“</span><p><?= e($testimonial['text']) ?></p><div class="testimonial__author"><span><?= e(initials($testimonial['name'])) ?></span><div><strong><?= e($testimonial['name']) ?></strong><small><?= e($testimonial['role']) ?></small></div></div></article><?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section faq-section section--soft">
            <div class="container">
                <?php render_section_heading('VOUS AVEZ DES QUESTIONS ?', 'Les réponses aux questions <em>essentielles.</em>', 'Tout ce qu’il faut savoir pour avancer avec sérénité.', 'center'); ?>
                <?php render_faqs($faqs, true); ?>
                <div class="center-action"><a class="text-link text-link--dark" href="<?= page_url('faq') ?>">Voir toutes les questions <span>↗</span></a></div>
            </div>
        </section>

        <section class="section blog-preview">
            <div class="container">
                <div class="section-heading section-heading--split"><div><p class="eyebrow">NOS ACTUALITÉS</p><h2>Les dernières <em>informations.</em></h2></div><a class="button button--outline-dark" href="<?= page_url('blog') ?>">Toutes les actualités <span>↗</span></a></div>
                <div class="blog-grid blog-grid--three"><?php foreach (array_slice($posts, 0, 3) as $post) { render_blog_card($post); } ?></div>
            </div>
        </section>

        <section class="section partners-section">
            <div class="container">
                <?php render_section_heading('ILS NOUS ACCOMPAGNENT', 'Des partenaires de <em>confiance.</em>', 'Nous travaillons avec des acteurs engagés pour vous offrir des projets solides et durables.', 'center'); ?>
                <div class="partner-grid">
                    <?php foreach (['partner_one', 'partner_two', 'partner_three', 'partner_four'] as $partner): ?><div class="partner-logo"><img src="<?= img_url($images[$partner]) ?>" alt="Partenaire GELPAZ IMMO" loading="lazy"></div><?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section contact-strip">
            <div class="container contact-strip__inner"><div><p class="eyebrow eyebrow--light">BESOIN D’UN CONSEIL ?</p><h2>Votre projet immobilier<br><em>commence ici.</em></h2></div><a class="button button--light" href="<?= page_url('contact') ?>">Nous contacter <span>↗</span></a></div>
        </section>
    </main>
<?php elseif ($current_page === 'about'): ?>
    <?php render_interior_hero('Qui sommes-nous ?', 'À propos', $images['hero_alt']); ?>
    <main>
        <section class="section">
            <div class="container split-section split-section--about"><div class="split-section__content"><p class="eyebrow">NOTRE HISTOIRE</p><h2>Créer de la valeur par <em>l’immobilier.</em></h2><p class="lead">GELPAZ IMMO SA est une société de promotion et de vente immobilière officiellement créée en 2009, faisant suite à l’agence immobilière GELPAZ SARL qui existait depuis 1987.</p><p>Née de la volonté de garantir à chaque Burkinabè un logement décent, notre entreprise place l’humain, l’écoute et la qualité au cœur de son action. Notre connaissance du terrain nous permet d’accompagner chaque client avec justesse.</p><a class="button button--dark" href="<?= page_url('contact') ?>">Échanger avec nous <span>↗</span></a></div><div class="split-section__media media-frame"><img src="<?= img_url($images['villa']) ?>" alt="Projet immobilier Gelpaz" loading="lazy"><div class="experience-badge"><strong>30<sup>+</sup></strong><span>années<br>d’expérience</span></div></div></div>
            <div class="container stats-banner"><div><strong>1987</strong><span>Les débuts de GELPAZ</span></div><div><strong>2009</strong><span>Création de GELPAZ IMMO SA</span></div><div><strong>100%</strong><span>Engagement à vos côtés</span></div><div><strong>BF</strong><span>Une expertise locale</span></div></div>
        </section>
        <section class="section section--soft"><div class="container values-layout"><div><p class="eyebrow">NOS VALEURS</p><h2>Guidés par la confiance<br>et <em>l’excellence.</em></h2></div><div class="value-list"><article><span>01</span><div><h3>Écoute active</h3><p>Comprendre vos attentes avant de vous proposer une solution.</p></div></article><article><span>02</span><div><h3>Intégrité</h3><p>Vous accompagner avec transparence à chaque décision.</p></div></article><article><span>03</span><div><h3>Professionnalisme</h3><p>Mettre notre expérience au service de la réussite de votre projet.</p></div></article><article><span>04</span><div><h3>Innovation</h3><p>Faire évoluer l’expérience immobilière pour la rendre plus simple.</p></div></article></div></div></section>
        <section class="section"><div class="container"><div class="section-heading section-heading--center"><p class="eyebrow">NOTRE ÉQUIPE</p><h2>Des experts derrière chaque <em>projet.</em></h2></div><div class="team-grid team-grid--large"><?php foreach ($team as $member) { ?><article class="team-card"><img src="<?= img_url($member['image']) ?>" alt="<?= e($member['name']) ?>" loading="lazy"><h3><?= e($member['name']) ?></h3><p><?= e($member['role']) ?></p></article><?php } ?></div></div></section>
        <?php render_cta_band('Faisons grandir votre projet immobilier.', 'Dites-nous ce qui compte pour vous et construisons la suite ensemble.'); ?>
    </main>
<?php elseif ($current_page === 'services'): ?>
    <?php render_interior_hero('Nos activités', 'Nos activités', $images['hero']); ?>
    <main>
        <section class="section"><div class="container"><?php render_section_heading('NOTRE SAVOIR-FAIRE', 'Des solutions immobilières <em>complètes.</em>', 'Une approche globale, pensée pour répondre aux réalités de chaque projet.', 'center'); ?><div class="service-cards service-cards--large"><?php foreach ($services as $service): ?><article class="service-card"><span class="service-card__icon"><?= e($service['icon']) ?></span><h3><?= e($service['title']) ?></h3><p><?= e($service['text']) ?></p><a class="text-link text-link--dark" href="<?= page_url('contact') ?>">En savoir plus <span>↗</span></a></article><?php endforeach; ?></div></div></section>
        <section class="section section--soft"><div class="container process-feature process-feature--services"><div class="process-feature__image"><img src="<?= img_url($images['villa']) ?>" alt="Accompagnement immobilier" loading="lazy"></div><div class="process-feature__content"><p class="eyebrow">NOTRE PROCESSUS</p><h2>Des étapes simples vers le <em>succès.</em></h2><p>Parce qu’un projet immobilier mérite de la clarté, nous vous guidons du premier échange jusqu’à la concrétisation.</p><a class="button button--dark" href="<?= page_url('contact') ?>">Prendre rendez-vous <span>↗</span></a></div></div><div class="container steps-row steps-row--cards"><div><span>01</span><h3>Premier échange</h3><p>Nous découvrons votre besoin, vos envies et votre budget.</p></div><div><span>02</span><h3>Choix du bien</h3><p>Nous vous présentons les opportunités qui vous correspondent.</p></div><div><span>03</span><h3>Accompagnement</h3><p>Nous restons présents et disponibles pour répondre à toutes vos questions.</p></div><div><span>04</span><h3>Finalisation</h3><p>Votre projet se concrétise dans un climat de confiance.</p></div></div></section>
        <?php render_cta_band(); ?>
    </main>
<?php elseif ($current_page === 'properties'): ?>
    <?php render_interior_hero('Nos logements', 'Nos offres', $images['hero']); ?>
    <main>
        <section class="section properties-page"><div class="container"><div class="section-heading section-heading--split"><div><p class="eyebrow">NOS OFFRES IMMOBILIÈRES</p><h2>Votre prochaine adresse<br><em>est peut-être ici.</em></h2></div><p class="section-heading__copy">Vente ou location, découvrez nos logements pensés pour une vie confortable et durable au Burkina Faso.</p></div><div class="filter-bar"><span><b><?= count($visible_properties) ?></b> propriétés trouvées</span><div class="filter-links"><a class="<?= $property_filter === 'all' ? 'is-active' : '' ?>" href="<?= page_url('properties') ?>">Toutes</a><a class="<?= $property_filter === 'vente' ? 'is-active' : '' ?>" href="<?= page_url('properties', ['filter' => 'vente']) ?>">Vente</a><a class="<?= $property_filter === 'location' ? 'is-active' : '' ?>" href="<?= page_url('properties', ['filter' => 'location']) ?>">Location</a></div><select aria-label="Trier les propriétés"><option>Plus récent d’abord</option><option>Surface croissante</option></select></div><div class="property-grid"><?php foreach ($visible_properties as $property) { render_property_card($property); } ?></div></div></section>
        <section class="section section--soft"><div class="container property-callout"><div><p class="eyebrow">VOUS NE TROUVEZ PAS VOTRE BONHEUR ?</p><h2>Parlons de votre recherche<br><em>sur mesure.</em></h2></div><a class="button button--dark" href="<?= page_url('contact') ?>">Nous contacter <span>↗</span></a></div></section>
    </main>
<?php elseif ($current_page === 'property'): ?>
    <?php render_interior_hero($selected_property['title'], 'Détails de la propriété', $selected_property['image']); ?>
    <main>
        <section class="section property-detail"><div class="container property-detail__layout"><aside class="property-summary"><p class="eyebrow">FICHE PROPRIÉTÉ</p><h2><?= e($selected_property['title']) ?></h2><p class="property-summary__location">⌖ <?= e($selected_property['location']) ?></p><div class="summary-price"><span><?= e($selected_property['category']) ?></span><strong><?= e($selected_property['price']) ?></strong></div><div class="summary-list"><div><span>Modèle</span><b><?= e($selected_property['model']) ?></b></div><div><span>Surface bâtie</span><b><?= e($selected_property['area']) ?></b></div><div><span>Chambres</span><b><?= e($selected_property['beds']) ?></b></div><div><span>Salles d’eau</span><b><?= e($selected_property['baths']) ?></b></div></div><a class="button button--dark button--full" href="<?= page_url('contact') ?>">Contacter GELPAZ IMMO <span>↗</span></a></aside><div class="property-detail__main"><div class="property-gallery"><img class="property-gallery__main" src="<?= img_url($selected_property['image']) ?>" alt="<?= e($selected_property['title']) ?>"><div class="property-gallery__thumbs"><img src="<?= img_url($images['hero_alt']) ?>" alt="Vue de la propriété"><img src="<?= img_url($images['villa']) ?>" alt="Vue intérieure"><img src="<?= img_url($images['hero']) ?>" alt="Façade de la propriété"></div></div><div class="property-copy"><p class="eyebrow">DESCRIPTION</p><h2>Un lieu pensé pour <em>bien vivre.</em></h2><p><?= e($selected_property['description']) ?> Profitez d’un cadre harmonieux, de volumes généreux et d’un accompagnement GELPAZ IMMO à chaque étape.</p><div class="amenities"><span>⌂ Espace familial</span><span>✓ Cadre sécurisé</span><span>⌁ Finitions soignées</span><span>♡ Confort durable</span></div></div><div class="location-block"><p class="eyebrow">LOCALISATION</p><h3>Votre propriété au cœur de Ouagadougou</h3><div class="map-placeholder"><span>⌖</span><div><b><?= e($selected_property['location']) ?></b><small>GELPAZ IMMO · Burkina Faso</small></div></div></div></div></div></section><section class="section related-section"><div class="container"><?php render_section_heading('AUTRES PROPRIÉTÉS', 'Découvrez aussi nos <em>opportunités.</em>', '', 'center'); ?><div class="property-grid property-grid--three"><?php foreach (array_slice($properties, 1, 3) as $property) { render_property_card($property, true); } ?></div></div></section>
    </main>
<?php elseif ($current_page === 'blog'): ?>
    <?php render_interior_hero('Actualités', 'Actualités', $images['news_one']); ?>
    <main><section class="section blog-page"><div class="container"><div class="section-heading section-heading--center"><p class="eyebrow">NOS ACTUALITÉS</p><h2>Les tendances, conseils et <em>informations.</em></h2><p class="section-heading__copy">Restez informé sur le marché immobilier et les projets qui font bouger le Burkina Faso.</p></div><div class="blog-layout"><div class="blog-grid blog-grid--two"><?php foreach ($posts as $post) { render_blog_card($post); } ?></div><aside class="blog-sidebar"><div class="sidebar-card"><p class="eyebrow">À LA UNE</p><h3>Les dernières nouvelles de GELPAZ IMMO</h3><p>Suivez nos projets, nos conseils et les évolutions du secteur immobilier.</p><a class="text-link text-link--dark" href="<?= page_url('contact') ?>">Recevoir nos nouvelles <span>↗</span></a></div><div class="sidebar-card"><p class="eyebrow">CATÉGORIES</p><a href="#">Marché immobilier <span>↗</span></a><a href="#">Nos réalisations <span>↗</span></a><a href="#">Conseils <span>↗</span></a></div></aside></div></div></section></main>
<?php elseif ($current_page === 'post'): ?>
    <section class="post-hero"><div class="container"><?php render_header(false); ?><p class="eyebrow eyebrow--light">ACTUALITÉS · IMMOBILIER AU BURKINA FASO</p><h1>Construire un patrimoine<br><em>immobilier durable.</em></h1><div class="post-meta">Par GELPAZ IMMO <span>·</span> 18 février 2023 <span>·</span> Conseil immobilier</div></div></section>
    <main><section class="section single-post"><div class="container single-post__layout"><article><img class="single-post__image" src="<?= img_url($images['villa']) ?>" alt="Patrimoine immobilier"/><p class="eyebrow">INTRODUCTION</p><h2>Investir dans l’immobilier au Burkina Faso</h2><p>Construire un patrimoine immobilier est une décision importante. Chez GELPAZ IMMO, nous croyons qu’un projet réussi commence par une compréhension claire de vos objectifs et du marché.</p><h3>Comprendre les fondamentaux</h3><p>Choisir un bien, c’est aussi choisir un environnement, un horizon et une manière de vivre. Notre équipe vous aide à regarder au-delà de la transaction pour trouver une solution réellement adaptée.</p><h3>L’importance d’un accompagnement local</h3><p>Notre connaissance de Ouagadougou et des réalités du Burkina Faso nous permet de vous orienter avec pragmatisme, transparence et sérénité.</p><h3>Un projet qui dure</h3><p>De la première visite à la remise des clés, nous restons disponibles pour vous aider à faire les bons choix et à donner de la valeur à votre investissement.</p><div class="post-share"><span>Tags : immobilier · investissement · habitat</span><span>Partager : ◯ ◯ ◯</span></div></article><aside class="blog-sidebar"><div class="sidebar-card"><p class="eyebrow">ARTICLES RÉCENTS</p><?php foreach (array_slice($posts, 0, 4) as $post): ?><a class="recent-post" href="<?= page_url('post') ?>"><img src="<?= img_url($post['image']) ?>" alt=""><span><?= e($post['title']) ?><small><?= e($post['date']) ?></small></span></a><?php endforeach; ?></div></aside></div></section><section class="section section--soft"><div class="container"><?php render_section_heading('À LIRE AUSSI', 'Poursuivez votre <em>lecture.</em>', '', 'left'); ?><div class="blog-grid blog-grid--three"><?php foreach (array_slice($posts, 1, 3) as $post) { render_blog_card($post); } ?></div></div></section></main>
<?php elseif ($current_page === 'contact'): ?>
    <?php render_interior_hero('Nous contacter', 'Contacts', $images['villa']); ?>
    <main><section class="section contact-page"><div class="container contact-layout"><div class="contact-form-wrap"><p class="eyebrow">PARLONS DE VOTRE PROJET</p><h2>Connectons-nous<br>à votre <em>avenir.</em></h2><p class="lead">Utilisez le formulaire ci-dessous ou contactez-nous directement. Notre équipe vous répondra avec plaisir.</p><?php if ($contact_success): ?><div class="form-success" role="status">Merci, votre message a bien été reçu. Notre équipe GELPAZ IMMO vous recontactera prochainement.</div><?php endif; ?><form class="contact-form" action="<?= page_url('contact') ?>" method="post"><div class="form-grid"><label>Prénom<input type="text" name="first_name" placeholder="Votre prénom"></label><label>Nom<input type="text" name="last_name" placeholder="Votre nom"></label><label>E-mail *<input type="email" name="email" placeholder="vous@exemple.com" required></label><label>Téléphone<input type="tel" name="phone" placeholder="+226 ..."></label></div><label>Votre message<textarea name="message" rows="5" placeholder="Parlez-nous de votre projet..."></textarea></label><label class="checkbox-label"><input type="checkbox" required> <span>J’accepte que GELPAZ IMMO utilise mes informations pour me recontacter.</span></label><button class="button button--dark" type="submit">Envoyer le message <span>↗</span></button></form></div><div class="contact-aside"><div class="contact-aside__visual"><img src="<?= img_url($images['hero_alt']) ?>" alt="Projet immobilier" loading="lazy"><div><span>GELPAZ IMMO</span><strong>La différence.</strong></div></div><div class="contact-cards"><a href="tel:+22625371055"><span>☎</span><div><small>Téléphone</small><strong><?= e($site['phone']) ?></strong></div></a><a href="https://wa.me/22667308185" target="_blank" rel="noreferrer"><span>◔</span><div><small>WhatsApp</small><strong><?= e($site['whatsapp']) ?></strong></div></a><a href="mailto:<?= e($site['email']) ?>"><span>✉</span><div><small>E-mail</small><strong><?= e($site['email']) ?></strong></div></a></div></div></div></section><section class="map-section"><div class="map-placeholder map-placeholder--large"><span>⌖</span><div><b>GELPAZ IMMO</b><small><?= e($site['address']) ?></small></div></div></section><section class="section"><div class="container"><?php render_section_heading('BESOIN D’AIDE ?', 'Les réponses aux questions <em>fréquentes.</em>', '', 'center'); ?><?php render_faqs($faqs, true); ?></div></section></main>
<?php elseif ($current_page === 'team'): ?>
    <?php render_interior_hero('Notre équipe', 'Notre équipe', $images['hero_alt']); ?>
    <main><section class="section"><div class="container"><div class="section-heading section-heading--center"><p class="eyebrow">NOTRE ÉQUIPE</p><h2>Les experts derrière chaque <em>projet.</em></h2><p class="section-heading__copy">Une équipe passionnée, professionnelle et engagée à vos côtés.</p></div><div class="team-grid team-grid--large"><?php foreach ($team as $member) { ?><article class="team-card"><img src="<?= img_url($member['image']) ?>" alt="<?= e($member['name']) ?>" loading="lazy"><h3><?= e($member['name']) ?></h3><p><?= e($member['role']) ?></p></article><?php } ?></div></div></section><?php render_cta_band('Et si votre projet était le prochain ?', 'Parlons de ce qui vous ferait vraiment vous sentir chez vous.'); ?></main>
<?php elseif ($current_page === 'faq'): ?>
    <?php render_interior_hero('FAQ', 'Questions fréquentes', $images['hero_alt']); ?>
    <main><section class="section"><div class="container narrow-content"><?php render_section_heading('VOUS AVEZ DES QUESTIONS ?', 'Les réponses aux questions <em>essentielles.</em>', 'Nous avons rassemblé les informations les plus demandées par nos clients.', 'center'); ?><?php render_faqs($faqs); ?></div></section><section class="section section--soft"><div class="container"><?php render_section_heading('NOS ACTUALITÉS', 'Conseils et informations <em>immobilières.</em>', '', 'left'); ?><div class="blog-grid blog-grid--three"><?php foreach (array_slice($posts, 0, 3) as $post) { render_blog_card($post); } ?></div></div></section></main>
<?php elseif ($current_page === 'pricing'): ?>
    <?php render_interior_hero('Nos offres', 'Souscription logement', $images['hero_alt']); ?>
    <main><section class="section"><div class="container"><?php render_section_heading('SOUSCRIPTION LOGEMENT', 'Des solutions pour chaque <em>projet.</em>', 'Parlons de votre budget et de vos priorités pour trouver la formule la plus juste.', 'center'); ?><div class="pricing-grid"><article class="price-card"><p class="eyebrow">ESSENTIEL</p><h3>Projet serein</h3><strong>Sur mesure</strong><p>Un accompagnement personnalisé pour trouver et réserver votre logement.</p><a class="button button--outline-dark" href="<?= page_url('contact') ?>">En savoir plus <span>↗</span></a><ul><li>Écoute de votre besoin</li><li>Sélection de biens</li><li>Conseils immobiliers</li></ul></article><article class="price-card price-card--dark"><p class="eyebrow eyebrow--light">PRIVILÈGE</p><h3>Accompagnement complet</h3><strong>GELPAZ IMMO</strong><p>Un suivi de proximité jusqu’à la concrétisation de votre projet.</p><a class="button button--light" href="<?= page_url('contact') ?>">Parler à un conseiller <span>↗</span></a><ul><li>Visites &amp; réservation</li><li>Suivi administratif</li><li>Accompagnement dédié</li></ul></article><article class="price-card"><p class="eyebrow">INVESTISSEUR</p><h3>Valoriser votre patrimoine</h3><strong>Sur mesure</strong><p>Une expertise locale pour sécuriser vos décisions et vos rendements.</p><a class="button button--outline-dark" href="<?= page_url('contact') ?>">Échanger avec nous <span>↗</span></a><ul><li>Analyse du projet</li><li>Conseil stratégique</li><li>Suivi personnalisé</li></ul></article></div></div></section><section class="section section--soft"><div class="container testimonial-layout"><div class="testimonial-copy"><p class="eyebrow">TÉMOIGNAGES</p><h2>Ils parlent de leur <em>expérience.</em></h2></div><div class="testimonial-list"><?php foreach ($testimonials as $testimonial) { ?><article class="testimonial"><span class="quote-mark">“</span><p><?= e($testimonial['text']) ?></p><div class="testimonial__author"><span><?= e(initials($testimonial['name'])) ?></span><div><strong><?= e($testimonial['name']) ?></strong><small><?= e($testimonial['role']) ?></small></div></div></article><?php } ?></div></div></section></main>
<?php endif; ?>
<?php render_footer(); ?>
<script src="/assets/js/app.js"></script>
</body>
</html>

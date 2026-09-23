<?php
/** Page view: home */
?>
    <main id="main">
        <section class="home-hero" aria-label="GELPAZ IMMO, votre partenaire immobilier">
            <div class="hero-slideshow">
                <?php foreach ($hero_slides as $index => $slide): ?>
                    <figure class="hero-slide <?= $index === 0 ? 'is-active' : '' ?>" data-slide-index="<?= $index ?>">
                        <img src="<?= img_url($slide['image']) ?>" alt="<?= e($slide['alt']) ?>" width="835" height="467"<?= image_attrs($slide['image'], '100vw') ?> decoding="async" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                    </figure>
                <?php endforeach; ?>
            </div>
            <?php render_header(true); ?>
            <div class="home-hero__shade"></div>
            <p class="sr-only" data-slide-status aria-live="polite"></p>
            <div class="container home-hero__content">
                <p class="eyebrow eyebrow--light">VOTRE PARTENAIRE IMMOBILIER AU BURKINA FASO</p>
                <h1>La différence,<br><em>c’est notre</em> engagement.</h1>
                <p class="home-hero__copy">Des solutions immobilières pensées pour vous, un accompagnement qui fait toute la différence.</p>
                <div class="button-row">
                    <a class="button button--accent" href="<?= page_url('properties') ?>">Découvrir nos logements <?= icon('arrow-up-right') ?></a>
                    <a class="button button--outline-light" href="<?= page_url('contact') ?>">Parler à un conseiller</a>
                </div>
                <form class="property-search" action="<?= page_url('properties') ?>" method="get">
                    <input type="hidden" name="page" value="properties">
                    <div class="property-search__intro"><span class="property-search__mark" aria-hidden="true"><?= icon('search') ?></span><div><strong>Trouvez votre prochain logement</strong><small>Affinez votre recherche en quelques secondes.</small></div></div>
                    <label>Projet<select name="filter"><option value="all">Acheter ou louer</option><option value="vente">Acheter</option><option value="location">Louer</option></select></label>
                    <label>Zone<select name="location"><option value="all">Toutes les zones</option><option value="ouagadougou">Ouagadougou</option><option value="centre">Centre</option><option value="bassinko">Bassinko</option></select></label>
                    <label>Chambres<select name="beds"><option value="all">Toutes</option><option value="2">2 chambres</option><option value="3">3 chambres</option><option value="4">4 chambres</option></select></label>
                    <button class="button button--accent" type="submit">Voir les biens <?= icon('arrow-up-right') ?></button>
                </form>
                <div class="home-hero__proof">
                    <span class="avatar-stack"><i>G</i><i>I</i><i>M</i></span>
                    <span><b>+30 ans</b><small>d’expérience immobilière</small></span>
                    <span class="proof-line"></span>
                    <span><b>100%</b><small>d’écoute &amp; d’engagement</small></span>
                </div>
            </div>
            <div class="hero-controls" aria-label="Contrôles du diaporama">
                <button class="hero-control hero-control--previous" type="button" aria-label="Image précédente"><?= icon('arrow-left') ?></button>
                <div class="hero-dots">
                    <?php foreach ($hero_slides as $index => $slide): ?>
                        <button type="button" aria-label="Afficher l’image <?= $index + 1 ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" data-slide-to="<?= $index ?>"><span></span></button>
                    <?php endforeach; ?>
                </div>
                <button class="hero-control hero-control--next" type="button" aria-label="Image suivante"><?= icon('arrow-right') ?></button>
                <button class="hero-control hero-control--pause" type="button" aria-label="Mettre le diaporama en pause"><?= icon('pause') ?></button>
            </div>
            <a class="scroll-cue" href="#intro"><?= icon('arrow-down') ?> Découvrir</a>
        </section>

        <section class="section intro-section" id="intro">
            <div class="container split-section">
                <div class="split-section__media media-frame">
                    <?php render_image($images['hero'], 'Une maison proposée par Gelpaz Immo', '(max-width: 900px) 92vw, 560px'); ?>
                    <span class="media-frame__caption">L’immobilier avec une vision humaine</span>
                </div>
                <div class="split-section__content">
                    <p class="eyebrow">GELPAZ IMMO · LA DIFFÉRENCE</p>
                    <h2>Créer de la valeur,<br><em>habiter mieux.</em></h2>
                    <p class="lead">Depuis plus de 30 ans, GELPAZ IMMO accompagne les familles et les investisseurs dans la concrétisation de leurs projets immobiliers au Burkina Faso.</p>
                    <p>Nous plaçons l’écoute, la transparence et le professionnalisme au cœur de chaque relation pour vous proposer une expérience simple, sereine et adaptée à vos attentes.</p>
                    <a class="text-link text-link--dark" href="<?= page_url('about') ?>">Découvrir notre histoire <?= icon('arrow-up-right') ?></a>
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
                                <?php render_image($property['image'], $property['title'], '(max-width: 900px) 92vw, 760px'); ?>
                                <span class="play-dot"><?= icon('arrow-up-right') ?></span>
                            </a>
                            <div class="featured-property__info">
                                <div><p><?= e($property['location']) ?></p><h3><?= e($property['title']) ?></h3></div>
                                <strong><?= e($property['price']) ?></strong>
                            </div>
                            <div class="featured-property__meta"><span><?= e($property['beds']) ?> chambres</span><span><?= e($property['baths']) ?> salles d’eau</span><span><?= e($property['area']) ?></span></div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="center-action"><a class="button button--light" href="<?= page_url('properties') ?>">Voir tous nos logements <?= icon('arrow-up-right') ?></a></div>
            </div>
        </section>

        <section class="section services-preview">
            <div class="container">
                <?php render_section_heading('NOTRE SAVOIR-FAIRE', 'Des solutions immobilières <em>complètes.</em>', 'De la recherche à la réalisation, nous sommes à vos côtés.', 'center'); ?>
                <div class="service-cards">
                    <?php foreach ($services as $service): ?>
                        <article class="service-card"><span class="service-card__icon" aria-hidden="true"><?= icon($service['icon']) ?></span><h3><?= e($service['title']) ?></h3><p><?= e($service['text']) ?></p><a href="<?= page_url('services') ?>" aria-label="En savoir plus sur <?= e($service['title']) ?>"><?= icon('arrow-up-right') ?></a></article>
                    <?php endforeach; ?>
                </div>
                <div class="process-feature">
                    <div class="process-feature__image"><?php render_image($images['villa'], 'Une villa Gelpaz Immo', '(max-width: 900px) 92vw, 520px'); ?></div>
                    <div class="process-feature__content"><p class="eyebrow">SIMPLE, CLAIR, HUMAIN</p><h2>Votre projet,<br><em>notre accompagnement.</em></h2><p>Choisissez votre propriété, échangez avec notre équipe et avancez sereinement vers la réalisation de votre projet.</p><a class="button button--dark" href="<?= page_url('services') ?>">Comment ça marche ? <?= icon('arrow-up-right') ?></a></div>
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
                    <?php foreach ($team as $member): ?><?php render_team_card($member); ?><?php endforeach; ?>
                </div>
                <div class="center-action"><a class="text-link text-link--dark" href="<?= page_url('about') ?>">Faire connaissance avec GELPAZ <?= icon('arrow-up-right') ?></a></div>
            </div>
        </section>

        <section class="section testimonials-section">
            <div class="container testimonial-layout">
                <div class="testimonial-copy"><p class="eyebrow">ILS NOUS FONT CONFIANCE</p><h2>Écoutez ceux qui ont choisi <em>Gelpaz.</em></h2><p>Chaque projet est unique. Les histoires de nos clients sont la meilleure preuve de notre engagement.</p><a class="button button--dark" href="<?= page_url('contact') ?>">Parlons de votre projet <?= icon('arrow-up-right') ?></a></div>
                <div class="testimonial-list">
                    <?php foreach ($testimonials as $testimonial): ?><article class="testimonial"><span class="quote-mark">“</span><p><?= e($testimonial['text']) ?></p><div class="testimonial__author"><span><?= e(initials($testimonial['name'])) ?></span><div><strong><?= e($testimonial['name']) ?></strong><small><?= e($testimonial['role']) ?></small></div></div></article><?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section faq-section section--soft">
            <div class="container">
                <?php render_section_heading('VOUS AVEZ DES QUESTIONS ?', 'Les réponses aux questions <em>essentielles.</em>', 'Tout ce qu’il faut savoir pour avancer avec sérénité.', 'center'); ?>
                <?php render_faqs($faqs, true); ?>
                <div class="center-action"><a class="text-link text-link--dark" href="<?= page_url('faq') ?>">Voir toutes les questions <?= icon('arrow-up-right') ?></a></div>
            </div>
        </section>

        <section class="section blog-preview">
            <div class="container">
                <div class="section-heading section-heading--split"><div><p class="eyebrow">NOS ACTUALITÉS</p><h2>Les dernières <em>informations.</em></h2></div><a class="button button--outline-dark" href="<?= page_url('blog') ?>">Toutes les actualités <?= icon('arrow-up-right') ?></a></div>
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
            <div class="container contact-strip__inner"><div><p class="eyebrow eyebrow--light">BESOIN D’UN CONSEIL ?</p><h2>Votre projet immobilier<br><em>commence ici.</em></h2></div><a class="button button--light" href="<?= page_url('contact') ?>">Nous contacter <?= icon('arrow-up-right') ?></a></div>
        </section>
    </main>

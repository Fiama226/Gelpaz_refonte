<?php
function render_header(bool $hero = false): void
{
    global $site;
    $page = $GLOBALS['current_page'] ?? 'home';
    $about_active = in_array($page, ['about', 'team'], true);
    $offers_active = in_array($page, ['properties', 'property', 'pricing'], true);
    $blog_active = in_array($page, ['blog', 'post'], true);
    ?>
    <header class="site-header <?= $hero ? 'site-header--overlay' : 'site-header--solid' ?>">
        <div class="container header-inner">
            <a class="brand" href="<?= page_url('home') ?>" aria-label="GELPAZ IMMO, accueil">
                <img src="/assets/images/logo-white.png" alt="" width="270" height="165">
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation"><span></span><span></span><span></span><b class="sr-only">Ouvrir le menu</b></button>
            <nav id="main-navigation" class="main-nav" aria-label="Navigation principale">
                <a class="nav-link<?= $page === 'home' ? ' is-active' : '' ?>" href="<?= page_url('home') ?>" <?= $page === 'home' ? 'aria-current="page"' : '' ?>><span class="nav-label">Accueil</span></a>
                <div class="nav-dropdown">
                    <button class="nav-link<?= $about_active ? ' is-active' : '' ?>" type="button" aria-expanded="false" aria-controls="about-menu"><span class="nav-label">À propos</span><?= icon('chevron-down', 'nav-chevron') ?></button>
                    <div class="nav-dropdown__menu" id="about-menu">
                        <a href="<?= page_url('about') ?>" <?= $page === 'about' ? 'aria-current="page"' : '' ?>>Qui sommes-nous ?</a>
                        <a href="<?= page_url('about', ['section' => 'values']) ?>">Missions, visions &amp; valeurs</a>
                        <a href="<?= page_url('team') ?>" <?= $page === 'team' ? 'aria-current="page"' : '' ?>>Notre équipe</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <button class="nav-link<?= $offers_active ? ' is-active' : '' ?>" type="button" aria-expanded="false" aria-controls="offers-menu"><span class="nav-label">Nos offres</span><?= icon('chevron-down', 'nav-chevron') ?></button>
                    <div class="nav-dropdown__menu" id="offers-menu">
                        <a href="<?= page_url('properties') ?>" <?= in_array($page, ['properties', 'property'], true) ? 'aria-current="page"' : '' ?>>Nos logements</a>
                        <a href="<?= page_url('properties', ['filter' => 'location']) ?>">Nos sites</a>
                        <a href="<?= page_url('pricing') ?>" <?= $page === 'pricing' ? 'aria-current="page"' : '' ?>>Souscription logement</a>
                    </div>
                </div>
                <a class="nav-link<?= $page === 'services' ? ' is-active' : '' ?>" href="<?= page_url('services') ?>" <?= $page === 'services' ? 'aria-current="page"' : '' ?>><span class="nav-label">Nos activités</span></a>
                <a class="nav-link<?= $blog_active ? ' is-active' : '' ?>" href="<?= page_url('blog') ?>" <?= $blog_active && $page === 'blog' ? 'aria-current="page"' : '' ?>><span class="nav-label">Actualités</span></a>
                <a class="nav-link<?= $page === 'contact' ? ' is-active' : '' ?>" href="<?= page_url('contact') ?>" <?= $page === 'contact' ? 'aria-current="page"' : '' ?>><span class="nav-label">Contact</span></a>
                <a class="mobile-contact-link" href="<?= page_url('contact') ?>">Parler à un conseiller <?= icon('arrow-right') ?></a>
            </nav>
            <a class="button button--light header-cta" href="<?= page_url('contact') ?>">Parler à un conseiller <?= icon('arrow-up-right') ?></a>
        </div>
    </header>
    <?php
}

function render_footer(): void
{
    global $site;
    ?>
    <footer class="site-footer">
        <div class="container footer-top">
            <div class="footer-newsletter">
                <p class="eyebrow eyebrow--light">GELPAZ IMMO</p>
                <h2>Construisons<br><em>votre avenir</em> ensemble.</h2>
                <a class="button button--accent footer-wa" href="<?= e(whatsapp_link('Bonjour GELPAZ IMMO, j’aimerais échanger sur mon projet immobilier.')) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> Discuter sur WhatsApp</a>
                <p class="form-note"><?= e($site['hours']) ?> — réponse rapide du lundi au vendredi.</p>
            </div>
            <div class="footer-column">
                <h3>Navigation</h3>
                <a href="<?= page_url('home') ?>">Accueil</a>
                <a href="<?= page_url('about') ?>">À propos</a>
                <a href="<?= page_url('properties') ?>">Nos logements</a>
                <a href="<?= page_url('services') ?>">Nos activités</a>
                <a href="<?= page_url('blog') ?>">Actualités</a>
            </div>
            <div class="footer-column">
                <h3>Nos offres</h3>
                <a href="<?= page_url('properties') ?>">Vente</a>
                <a href="<?= page_url('properties', ['filter' => 'location']) ?>">Location</a>
                <a href="<?= page_url('services') ?>">Gestion immobilière</a>
                <a href="<?= page_url('contact') ?>">Conseil immobilier</a>
                <a href="<?= page_url('pricing') ?>">Souscription</a>
            </div>
            <div class="footer-column">
                <h3>Nous joindre</h3>
                <a href="tel:+22625371055"><?= e($site['phone']) ?></a>
                <a href="https://wa.me/22667308185" target="_blank" rel="noreferrer"><?= e($site['whatsapp']) ?></a>
                <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
                <p><?= e($site['address']) ?></p>
            </div>
        </div>
        <div class="container footer-bottom">
            <a class="brand brand--footer" href="<?= page_url('home') ?>"><img src="/assets/images/logo-white.png" alt="GELPAZ IMMO" width="270" height="165"></a>
            <p>© <?= date('Y') ?> GELPAZ IMMO. Tous droits réservés.</p>
            <div class="footer-socials" aria-label="Nous contacter">
                <a href="https://wa.me/22667308185" target="_blank" rel="noopener noreferrer" aria-label="Contacter GELPAZ IMMO sur WhatsApp"><?= icon('whatsapp') ?></a>
                <a href="tel:+22625371055" aria-label="Appeler GELPAZ IMMO"><?= icon('phone') ?></a>
                <a href="mailto:<?= e($site['email']) ?>" aria-label="Écrire à GELPAZ IMMO"><?= icon('mail') ?></a>
            </div>
        </div>
    </footer>
    <a class="whatsapp-float" href="<?= e(whatsapp_link('Bonjour GELPAZ IMMO, j’aimerais échanger sur mon projet immobilier.')) ?>" target="_blank" rel="noopener" aria-label="Discuter avec GELPAZ IMMO sur WhatsApp"><?= icon('whatsapp') ?><span class="whatsapp-float__label">Discuter sur WhatsApp</span></a>
    <button class="back-to-top" type="button" aria-label="Retour en haut"><?= icon('arrow-up') ?></button>
    <?php
}

function render_interior_hero(string $title, string $crumb, string $image): void
{
    render_header(true);
    ?>
    <section class="page-hero" style="--hero-image: url('<?= img_url($image) ?>')">
        <div class="container page-hero__content">
            <p class="eyebrow eyebrow--light">GELPAZ IMMO · LA DIFFÉRENCE</p>
            <h1><?= $title ?></h1>
            <div class="breadcrumbs"><a href="<?= page_url('home') ?>">Accueil</a><span class="crumb-sep"><?= icon('arrow-right') ?></span><span><?= e($crumb) ?></span></div>
        </div>
    </section>
    <?php
}

function render_cta_band(string $title = 'Prêt à concrétiser votre projet immobilier ?', string $copy = 'Notre équipe vous accompagne avec écoute, transparence et expertise.') : void
{
    global $images;
    ?>
    <section class="cta-band" style="--cta-image: url('<?= img_url($images['hero_alt']) ?>')">
        <div class="cta-band__overlay"></div>
        <div class="container cta-band__inner">
            <p class="eyebrow eyebrow--light">VOTRE PROJET COMMENCE ICI</p>
            <h2><?= $title ?></h2>
            <p><?= e($copy) ?></p>
            <a class="button button--light" href="<?= page_url('contact') ?>">Échangeons sur votre projet <?= icon('arrow-up-right') ?></a>
        </div>
    </section>
    <?php
}

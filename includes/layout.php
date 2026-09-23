<?php
function render_header(bool $hero = false): void
{
    global $site;
    ?>
    <header class="site-header <?= $hero ? 'site-header--overlay' : 'site-header--solid' ?>">
        <div class="container header-inner">
            <a class="brand" href="<?= page_url('home') ?>" aria-label="GELPAZ IMMO, accueil">
                <img src="/assets/images/logo.png" alt="GELPAZ IMMO">
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation"><span></span><span></span><span></span><b class="sr-only">Ouvrir le menu</b></button>
            <nav id="main-navigation" class="main-nav" aria-label="Navigation principale">
                <a class="<?= is_page('home') ? 'is-active' : '' ?>" href="<?= page_url('home') ?>">Accueil</a>
                <div class="nav-dropdown">
                    <button type="button">À propos <span>⌄</span></button>
                    <div class="nav-dropdown__menu">
                        <a href="<?= page_url('about') ?>">Qui sommes-nous ?</a>
                        <a href="<?= page_url('about', ['section' => 'values']) ?>">Missions, visions &amp; valeurs</a>
                        <a href="<?= page_url('team') ?>">Notre équipe</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <button type="button">Nos offres <span>⌄</span></button>
                    <div class="nav-dropdown__menu">
                        <a href="<?= page_url('properties') ?>">Nos logements</a>
                        <a href="<?= page_url('properties', ['filter' => 'location']) ?>">Nos sites</a>
                        <a href="<?= page_url('pricing') ?>">Souscription logement</a>
                    </div>
                </div>
                <a class="<?= is_page('services') ? 'is-active' : '' ?>" href="<?= page_url('services') ?>">Nos activités</a>
                <a class="<?= in_array($GLOBALS['current_page'] ?? '', ['blog', 'post'], true) ? 'is-active' : '' ?>" href="<?= page_url('blog') ?>">Actualités</a>
                <a class="<?= is_page('contact') ? 'is-active' : '' ?>" href="<?= page_url('contact') ?>">Contacts</a>
            </nav>
            <a class="button button--light header-cta" href="<?= page_url('contact') ?>">Parler à un conseiller <span>↗</span></a>
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
                <form class="newsletter-form" action="<?= page_url('home') ?>" method="post">
                    <label class="sr-only" for="newsletter-email">Votre adresse e-mail</label>
                    <input id="newsletter-email" type="email" placeholder="Votre adresse e-mail" required>
                    <button type="submit" aria-label="S’inscrire à la newsletter">↗</button>
                </form>
                <p class="form-note">Recevez nos opportunités et actualités immobilières.</p>
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
                <a href="<?= page_url('contact') ?>">Souscription</a>
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
            <a class="brand brand--footer" href="<?= page_url('home') ?>"><img src="/assets/images/logo.png" alt="GELPAZ IMMO"></a>
            <p>© <?= date('Y') ?> GELPAZ IMMO. Tous droits réservés.</p>
            <div class="footer-socials" aria-label="Réseaux sociaux">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Youtube">▶</a>
                <a href="#" aria-label="LinkedIn">in</a>
                <a href="#" aria-label="WhatsApp">◔</a>
            </div>
        </div>
    </footer>
    <button class="back-to-top" type="button" aria-label="Retour en haut">↑</button>
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
            <div class="breadcrumbs"><a href="<?= page_url('home') ?>">Accueil</a><span>→</span><span><?= e($crumb) ?></span></div>
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
            <a class="button button--light" href="<?= page_url('contact') ?>">Échangeons sur votre projet <span>↗</span></a>
        </div>
    </section>
    <?php
}

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
                    <button type="button" aria-expanded="false" aria-controls="about-menu">À propos <svg class="nav-chevron" aria-hidden="true" viewBox="0 0 16 16"><path d="m4 6 4 4 4-4"/></svg></button>
                    <div class="nav-dropdown__menu" id="about-menu">
                        <a href="<?= page_url('about') ?>">Qui sommes-nous ?</a>
                        <a href="<?= page_url('about', ['section' => 'values']) ?>">Missions, visions &amp; valeurs</a>
                        <a href="<?= page_url('team') ?>">Notre équipe</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <button type="button" aria-expanded="false" aria-controls="offers-menu">Nos offres <svg class="nav-chevron" aria-hidden="true" viewBox="0 0 16 16"><path d="m4 6 4 4 4-4"/></svg></button>
                    <div class="nav-dropdown__menu" id="offers-menu">
                        <a href="<?= page_url('properties') ?>">Nos logements</a>
                        <a href="<?= page_url('properties', ['filter' => 'location']) ?>">Nos sites</a>
                        <a href="<?= page_url('pricing') ?>">Souscription logement</a>
                    </div>
                </div>
                <a class="<?= is_page('services') ? 'is-active' : '' ?>" href="<?= page_url('services') ?>">Nos activités</a>
                <a class="<?= in_array($GLOBALS['current_page'] ?? '', ['blog', 'post'], true) ? 'is-active' : '' ?>" href="<?= page_url('blog') ?>">Actualités</a>
                <a class="<?= is_page('contact') ? 'is-active' : '' ?>" href="<?= page_url('contact') ?>">Contact</a>
                <a class="mobile-contact-link" href="<?= page_url('contact') ?>">Parler à un conseiller <span aria-hidden="true">→</span></a>
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
                <form class="newsletter-form" action="<?= page_url('home') ?>" method="post" aria-describedby="newsletter-note">
                    <label class="sr-only" for="newsletter-email">Votre adresse e-mail</label>
                    <input id="newsletter-email" type="email" placeholder="Votre adresse e-mail" required disabled aria-describedby="newsletter-note">
                    <button type="submit" aria-label="S’inscrire à la newsletter" disabled>↗</button>
                </form>
                <p class="form-note" id="newsletter-note">Inscription à la newsletter prochainement disponible.</p>
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
            <div class="footer-socials" aria-label="Nous contacter">
                <a href="https://wa.me/22667308185" target="_blank" rel="noopener noreferrer" aria-label="Contacter GELPAZ IMMO sur WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.8a8.4 8.4 0 0 1-12.4 7.4L4 20l.8-4a8.4 8.4 0 1 1 15.7-4.2Z"/><path d="M9 8.7c.2-.4.4-.4.7-.4h.4c.2 0 .3.1.4.4l.7 1.6c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4-.1.6.4.7 1 1.3 1.7 1.7.2.1.4.1.6-.1l.6-.6c.2-.2.4-.2.6-.1l1.5.7c.3.1.4.2.4.4 0 .3-.2 1.1-.7 1.4-.5.4-1.1.5-1.8.3-.7-.2-1.6-.6-2.7-1.5-1.3-1.1-2.2-2.5-2.4-3.5-.2-.8 0-1.4.4-1.9Z"/></svg></a>
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

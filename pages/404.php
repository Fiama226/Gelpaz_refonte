<?php
/** Page view: 404 */
?>
    <?php render_interior_hero('Page introuvable', 'Erreur 404', $images['hero_alt']); ?>
    <main id="main"><section class="section"><div class="container error-page"><div><p class="error-code" aria-hidden="true">404</p><h1>Cette page n’existe pas <em>(encore).</em></h1><p>La page recherchée a peut-être été déplacée ou renommée. Reprenons depuis un point connu : découvrez nos logements ou échangez directement avec un conseiller.</p><div class="button-row"><a class="button button--dark" href="<?= page_url('properties') ?>">Voir nos logements <?= icon('arrow-up-right') ?></a><a class="button button--outline-dark" href="<?= page_url('contact') ?>">Nous contacter</a></div></div></div></section></main>

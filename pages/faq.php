<?php
/** Page view: faq */
?>
    <?php render_interior_hero('FAQ', 'Questions fréquentes', $images['hero_alt']); ?>
    <main id="main"><section class="section"><div class="container narrow-content"><?php render_section_heading('VOUS AVEZ DES QUESTIONS ?', 'Les réponses aux questions <em>essentielles.</em>', 'Nous avons rassemblé les informations les plus demandées par nos clients.', 'center'); ?><?php render_faqs($faqs); ?></div></section><section class="section section--soft"><div class="container"><?php render_section_heading('NOS ACTUALITÉS', 'Conseils et informations <em>immobilières.</em>', '', 'left'); ?><div class="blog-grid blog-grid--three"><?php foreach (array_slice($posts, 0, 3) as $post) { render_blog_card($post); } ?></div></div></section></main>

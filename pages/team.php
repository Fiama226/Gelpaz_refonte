<?php
/** Page view: team */
?>
    <?php render_interior_hero('Notre équipe', 'Notre équipe', $images['hero_alt']); ?>
    <main><section class="section"><div class="container"><div class="section-heading section-heading--center"><p class="eyebrow">NOTRE ÉQUIPE</p><h2>Les experts derrière chaque <em>projet.</em></h2><p class="section-heading__copy">Une équipe passionnée, professionnelle et engagée à vos côtés.</p></div><div class="team-grid team-grid--large"><?php foreach ($team as $member) { ?><article class="team-card"><img src="<?= img_url($member['image']) ?>" alt="<?= e($member['name']) ?>" loading="lazy"><h3><?= e($member['name']) ?></h3><p><?= e($member['role']) ?></p></article><?php } ?></div></div></section><?php render_cta_band('Et si votre projet était le prochain ?', 'Parlons de ce qui vous ferait vraiment vous sentir chez vous.'); ?></main>

<?php
/**
 * Shared lightbox: opened by [data-lightbox-open] inside [data-gallery].
 * Included once per page from index.php.
 */
?>
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Galerie photos" hidden>
    <div class="lightbox__controls">
        <button type="button" data-lightbox-close aria-label="Fermer la galerie"><?= icon('close') ?></button>
    </div>
    <button class="lightbox__nav lightbox__nav--previous" type="button" data-lightbox-previous aria-label="Photo précédente"><?= icon('arrow-left') ?></button>
    <figure>
        <img alt="" data-lightbox-image>
        <figcaption class="lightbox__caption" data-lightbox-caption></figcaption>
    </figure>
    <button class="lightbox__nav lightbox__nav--next" type="button" data-lightbox-next aria-label="Photo suivante"><?= icon('arrow-right') ?></button>
</div>

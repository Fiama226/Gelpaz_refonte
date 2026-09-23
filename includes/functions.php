<?php
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function page_url(string $page = 'home', array $params = []): string
{
    static $paths = [
        'home' => '',
        'about' => 'nous-connaitre',
        'services' => 'nos-activites',
        'properties' => 'nos-offres-immobilieres',
        'property' => 'estate_property/',
        'blog' => 'blog-list-no-sidebar-2',
        'post' => 'article',
        'contact' => 'contact-us',
        'team' => 'team',
        'faq' => 'faq',
        'pricing' => 'souscription-logement',
    ];
    $path = $paths[$page] ?? '';
    if ($page === 'property') {
        $property_id = (string) ($params['id'] ?? 'modele-f4c');
        unset($params['id']);
        $path .= rawurlencode($property_id);
    }
    if ($page === 'post' && isset($params['slug'])) {
        $slug = (string) $params['slug'];
        unset($params['slug']);
        $path .= $slug !== '' ? '/' . rawurlencode($slug) : '';
    }
    $query = $params ? '?' . http_build_query($params) : '';
    return '/' . $path . $query;
}

function is_page(string $page): bool
{
    return ($GLOBALS['current_page'] ?? 'home') === $page;
}

function img_url(string $url): string
{
    return e($url);
}

/**
 * Smaller WordPress variant used as a graceful fallback (see the img error
 * handler in app.js) and as the second srcset candidate.
 */
function image_small(string $url): string
{
    return str_contains($url, '-835x467') ? str_replace('-835x467', '-525x328', $url) : '';
}

/**
 * srcset/sizes/data-fallback attributes for a Gelpaz media URL.
 */
function image_attrs(string $url, string $sizes = '100vw'): string
{
    $small = image_small($url);
    $attrs = ' sizes="' . e($sizes) . '"';
    if ($small !== '') {
        $attrs .= ' srcset="' . e($url) . ' 835w, ' . e($small) . ' 525w"';
        $attrs .= ' data-fallback="' . e($small) . '"';
    }
    return $attrs;
}

/**
 * <img> tag for Gelpaz media with width/height (avoids layout shift),
 * lazy loading and responsive candidates.
 */
/**
 * srcset/sizes attributes valid on <link rel="preload" as="image">.
 */
function image_preload_attrs(string $url, string $sizes = '100vw'): string
{
    $small = image_small($url);
    if ($small === '') {
        return '';
    }
    return ' imagesrcset="' . e($url) . ' 835w, ' . e($small) . ' 525w" imagesizes="' . e($sizes) . '"';
}

function render_image(string $url, string $alt, string $sizes = '100vw', string $class = '', bool $lazy = true, int $width = 835, int $height = 467): void
{
    echo '<img src="' . img_url($url) . '" alt="' . e($alt) . '"'
        . ($class !== '' ? ' class="' . e($class) . '"' : '')
        . ' width="' . $width . '" height="' . $height . '"'
        . image_attrs($url, $sizes)
        . ' loading="' . ($lazy ? 'lazy' : 'eager') . '" decoding="async">';
}

/**
 * OpenStreetMap embed (no API key required) centred on the agency.
 */
function map_embed_url(string $label = 'GELPAZ IMMO', float $lat = 12.3686, float $lon = -1.5275, float $span = 0.012): string
{
    $bbox = implode('%2C', [$lon - $span, $lat - $span / 2, $lon + $span, $lat + $span / 2]);
    return 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&layer=mapnik&marker=' . $lat . '%2C' . $lon;
}

function map_link_url(float $lat = 12.3686, float $lon = -1.5275): string
{
    return 'https://www.openstreetmap.org/?mlat=' . $lat . '&mlon=' . $lon . '#map=16/' . $lat . '/' . $lon;
}

function post_by_slug(string $slug): ?array
{
    foreach ($GLOBALS['posts'] as $post) {
        if (($post['slug'] ?? '') === $slug) {
            return $post;
        }
    }
    return null;
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name));
    $result = '';
    foreach (array_slice($parts ?: [], 0, 2) as $part) {
        $result .= strtoupper(substr($part, 0, 1));
    }
    return $result ?: 'G';
}

/**
 * Inline SVG icon from the shared sprite (see render_icon_sprite()).
 */
function icon(string $name, string $class = ''): string
{
    $safe = preg_replace('/[^a-z0-9\-]/', '', strtolower($name)) ?? '';
    $extra = $class !== '' ? ' ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') : '';
    return '<svg class="icon icon--' . $safe . $extra . '" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="#i-' . $safe . '" xlink:href="#i-' . $safe . '"></use></svg>';
}

/**
 * Prefilled WhatsApp link (main conversion channel).
 */
function whatsapp_link(string $text): string
{
    return 'https://wa.me/22667308185?text=' . rawurlencode($text);
}

/**
 * SVG sprite with all icons used across the site (stroke style, 24px grid).
 */
function render_icon_sprite(): void
{
    ?>
    <svg class="icon-sprite" aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute">
        <defs>
            <symbol id="i-arrow-up-right" viewBox="0 0 24 24"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></symbol>
            <symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></symbol>
            <symbol id="i-arrow-left" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></symbol>
            <symbol id="i-arrow-up" viewBox="0 0 24 24"><path d="M12 20V4"/><path d="m5 12 7-7 7 7"/></symbol>
            <symbol id="i-arrow-down" viewBox="0 0 24 24"><path d="M12 4v16"/><path d="m19 12-7 7-7-7"/></symbol>
            <symbol id="i-chevron-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
            <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20.5 20.5-4.2-4.2"/></symbol>
            <symbol id="i-home" viewBox="0 0 24 24"><path d="M3 10.2 12 3l9 7.2V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></symbol>
            <symbol id="i-key" viewBox="0 0 24 24"><circle cx="7.5" cy="15.5" r="4"/><path d="m10.5 12.5 8-8"/><path d="m15.5 7.5 2.5 2.5"/><path d="m18 5 3 3"/></symbol>
            <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 22s8-3.6 8-10V5.5L12 2 4 5.5V12c0 6.4 8 10 8 10z"/></symbol>
            <symbol id="i-message" viewBox="0 0 24 24"><path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7A8.5 8.5 0 1 1 21 11.5z"/></symbol>
            <symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></symbol>
            <symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/></symbol>
            <symbol id="i-map-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
            <symbol id="i-whatsapp" viewBox="0 0 24 24"><path d="M20.5 11.8a8.4 8.4 0 0 1-12.4 7.4L4 20l.8-4a8.4 8.4 0 1 1 15.7-4.2Z"/><path d="M9 8.7c.2-.4.4-.4.7-.4h.4c.2 0 .3.1.4.4l.7 1.6c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4-.1.6.4.7 1 1.3 1.7 1.7.2.1.4.1.6-.1l.6-.6c.2-.2.4-.2.6-.1l1.5.7c.3.1.4.2.4.4 0 .3-.2 1.1-.7 1.4-.5.4-1.1.5-1.8.3-.7-.2-1.6-.6-2.7-1.5-1.3-1.1-2.2-2.5-2.4-3.5-.2-.8 0-1.4.4-1.9Z"/></symbol>
            <symbol id="i-play" viewBox="0 0 24 24"><path d="M7 4.5 19.5 12 7 19.5z"/></symbol>
            <symbol id="i-pause" viewBox="0 0 24 24"><path d="M9 5v14"/><path d="M15 5v14"/></symbol>
            <symbol id="i-check" viewBox="0 0 24 24"><path d="m4 12.5 5 5L20 6.5"/></symbol>
            <symbol id="i-heart" viewBox="0 0 24 24"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21.2l7.8-7.8 1.1-1.1a5.5 5.5 0 0 0-.1-7.7z"/></symbol>
            <symbol id="i-close" viewBox="0 0 24 24"><path d="M6 6l12 12"/><path d="M18 6 6 18"/></symbol>
            <symbol id="i-tool" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9z"/></symbol>
        </defs>
    </svg>
    <?php
}

function property_by_id(string $id): ?array
{
    foreach ($GLOBALS['properties'] as $property) {
        if ($property['id'] === $id) {
            return $property;
        }
    }
    return null;
}

function page_title(string $page): string
{
    $titles = [
        'home' => 'Vente & location de logements à Ouagadougou',
        'about' => 'Qui sommes-nous ?',
        'services' => 'Nos activités',
        'properties' => 'Nos logements',
        'property' => 'Détails de la propriété',
        'blog' => 'Actualités',
        'post' => 'Actualité immobilière',
        'contact' => 'Nous contacter',
        'team' => 'Notre équipe',
        'faq' => 'Questions fréquentes',
        'pricing' => 'Nos offres',
        'legal' => 'Mentions légales & confidentialité',
        '404' => 'Page introuvable',
    ];
    return $titles[$page] ?? 'GELPAZ IMMO';
}

function render_property_card(array $property, bool $featured = false): void
{
    $class = $featured ? 'property-card property-card--featured' : 'property-card';
    ?>
    <article class="<?= $class ?>">
        <a class="property-card__media" href="<?= page_url('property', ['id' => $property['id']]) ?>">
            <?php render_image($property['image'], $property['title'], '(max-width: 760px) 92vw, (max-width: 1100px) 45vw, 360px'); ?>
            <span class="property-card__tag"><?= e($property['category']) ?></span>
            <span class="property-card__arrow" aria-hidden="true"><?= icon('arrow-up-right') ?></span>
        </a>
        <div class="property-card__body">
            <div class="eyebrow-row">
                <span><?= e($property['status']) ?></span>
                <span><?= e($property['location']) ?></span>
            </div>
            <div class="property-card__heading">
                <div>
                    <h3><a href="<?= page_url('property', ['id' => $property['id']]) ?>"><?= e($property['title']) ?></a></h3>
                    <p>Modèle <?= e($property['model']) ?></p>
                </div>
                <strong><?= e($property['price']) ?></strong>
            </div>
            <p class="property-card__description"><?= e($property['description']) ?></p>
            <div class="property-card__meta">
                <span><b><?= e($property['beds']) ?></b> chambres</span>
                <span><b><?= e($property['baths']) ?></b> salles d’eau</span>
                <span><b><?= e($property['area']) ?></b></span>
            </div>
            <a class="text-link property-card__cta" href="<?= e(whatsapp_link('Bonjour GELPAZ IMMO, je suis intéressé par le bien « ' . $property['title'] . ' » (modèle ' . $property['model'] . ').')) ?>" target="_blank" rel="noopener">Demander ce bien <?= icon('whatsapp') ?></a>
        </div>
    </article>
    <?php
}

function render_blog_card(array $post, bool $compact = false): void
{
    $class = $compact ? 'post-card post-card--compact' : 'post-card';
    $link = page_url('post', ['slug' => $post['slug'] ?? '']);
    ?>
    <article class="<?= $class ?>">
        <a class="post-card__media" href="<?= e($link) ?>">
            <?php render_image($post['image'], $post['title'], '(max-width: 760px) 92vw, 360px'); ?>
        </a>
        <div class="post-card__body">
            <time datetime="<?= e($post['iso'] ?? '') ?>"><?= e($post['date']) ?></time>
            <h3><a href="<?= e($link) ?>"><?= e($post['title']) ?></a></h3>
            <?php if (!$compact): ?><p><?= e($post['excerpt']) ?></p><?php endif; ?>
            <?php if (!$compact): ?><a class="text-link" href="<?= e($link) ?>">Lire l’article <?= icon('arrow-up-right') ?></a><?php endif; ?>
        </div>
    </article>
    <?php
}

function render_section_heading(string $eyebrow, string $title, string $copy = '', string $align = 'center'): void
{
    ?>
    <div class="section-heading section-heading--<?= e($align) ?>">
        <p class="eyebrow"><?= e($eyebrow) ?></p>
        <h2><?= $title ?></h2>
        <?php if ($copy): ?><p class="section-heading__copy"><?= e($copy) ?></p><?php endif; ?>
    </div>
    <?php
}

function render_faqs(array $faqs, bool $short = false): void
{
    $items = $short ? array_slice($faqs, 0, 4) : $faqs;
    ?>
    <div class="faq-grid">
        <?php foreach ($items as $index => $faq): ?>
            <details class="faq-item" <?= $index === 0 ? 'open' : '' ?>>
                <summary><?= e($faq['q']) ?><span aria-hidden="true"></span></summary>
                <div class="faq-item__answer"><p><?= e($faq['a']) ?></p></div>
            </details>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Team card. Renders the real portrait when one is provided, otherwise a
 * branded monogram instead of a stand-in stock photo.
 */
function render_team_card(array $member): void
{
    ?>
    <article class="team-card">
        <?php if (!empty($member['photo'])): ?>
            <?php render_image($member['photo'], $member['photo_alt'] !== '' ? $member['photo_alt'] : $member['name'], '(max-width: 760px) 92vw, 340px', 'team-card__photo'); ?>
        <?php else: ?>
            <div class="team-card__monogram" role="img" aria-label="<?= e($member['name']) ?> — portrait à venir"><span><?= e(initials($member['name'])) ?></span></div>
        <?php endif; ?>
        <h3><?= e($member['name']) ?></h3>
        <p><?= e($member['role']) ?></p>
    </article>
    <?php
}

function asset_version(string $relative_path): string
{
    $file = __DIR__ . '/../' . ltrim($relative_path, '/');
    $stamp = is_file($file) ? filemtime($file) : false;
    return $stamp ? (string) $stamp : '1';
}

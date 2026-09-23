<?php
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function page_url(string $page = 'home', array $params = []): string
{
    $query = array_merge(['page' => $page], $params);
    return 'index.php?' . http_build_query($query);
}

function is_page(string $page): bool
{
    return ($GLOBALS['current_page'] ?? 'home') === $page;
}

function img_url(string $url): string
{
    return e($url);
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
        'home' => 'La différence',
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
    ];
    return $titles[$page] ?? 'GELPAZ IMMO';
}

function render_property_card(array $property, bool $featured = false): void
{
    $class = $featured ? 'property-card property-card--featured' : 'property-card';
    ?>
    <article class="<?= $class ?>">
        <a class="property-card__media" href="<?= page_url('property', ['id' => $property['id']]) ?>">
            <img src="<?= img_url($property['image']) ?>" alt="<?= e($property['title']) ?>" loading="lazy">
            <span class="property-card__tag"><?= e($property['category']) ?></span>
            <span class="property-card__arrow" aria-hidden="true">↗</span>
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
        </div>
    </article>
    <?php
}

function render_blog_card(array $post, bool $compact = false): void
{
    $class = $compact ? 'post-card post-card--compact' : 'post-card';
    ?>
    <article class="<?= $class ?>">
        <a class="post-card__media" href="<?= page_url('post') ?>">
            <img src="<?= img_url($post['image']) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
        </a>
        <div class="post-card__body">
            <time><?= e($post['date']) ?></time>
            <h3><a href="<?= page_url('post') ?>"><?= e($post['title']) ?></a></h3>
            <?php if (!$compact): ?><p><?= e($post['excerpt']) ?></p><?php endif; ?>
            <?php if (!$compact): ?><a class="text-link" href="<?= page_url('post') ?>">Lire l’article <span>↗</span></a><?php endif; ?>
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

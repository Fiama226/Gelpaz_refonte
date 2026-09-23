<?php
require __DIR__ . '/data.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/layout.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$route_map = [
    'nous-connaitre' => 'about',
    'mission-vision-valeur' => 'about',
    'nos-offres-immobilieres' => 'properties',
    'properties-list-2' => 'properties',
    'nos-sites' => 'properties',
    'property_action_category/vente' => 'properties',
    'property_area/centre' => 'properties',
    'souscription-logement' => 'pricing',
    'nos-activites' => 'services',
    'blog-list-no-sidebar-2' => 'blog',
    'contact-us' => 'contact',
    'nos-realisations' => 'properties',
    'team' => 'team',
    'faq' => 'faq',
    'pricing' => 'pricing',
];
$route_page = $route_map[$path] ?? null;
if (str_starts_with($path, 'estate_property/')) {
    $route_page = 'property';
    $_GET['id'] = basename($path);
}
if ($route_page === 'properties' && $path === 'property_action_category/vente') {
    $_GET['filter'] = 'vente';
}
$page = $_GET['page'] ?? $route_page ?? 'home';
$allowed_pages = ['home', 'about', 'services', 'properties', 'property', 'blog', 'post', 'contact', 'team', 'faq', 'pricing'];
$current_page = in_array($page, $allowed_pages, true) ? $page : 'home';
$GLOBALS['current_page'] = $current_page;

$property_id = $_GET['id'] ?? 'modele-f4c';
$selected_property = property_by_id($property_id) ?: $properties[0];
$property_filter = strtolower(trim((string) ($_GET['filter'] ?? 'all')));
$visible_properties = array_values(array_filter($properties, static function (array $property) use ($property_filter): bool {
    return $property_filter === 'all' || strtolower($property['category']) === $property_filter;
}));
if ($visible_properties === []) {
    $visible_properties = $properties;
    $property_filter = 'all';
}
$contact_success = $current_page === 'contact'
    && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

function meta_description(string $page): string
{
    $descriptions = [
        'home' => 'GELPAZ IMMO, votre partenaire immobilier au Burkina Faso. Découvrez nos villas, nos offres et notre accompagnement personnalisé.',
        'about' => 'Découvrez GELPAZ IMMO, son histoire, ses valeurs et sa vision pour un habitat de qualité au Burkina Faso.',
        'services' => 'Vente, location, gestion et conseil immobilier : GELPAZ IMMO vous accompagne dans tous vos projets.',
        'properties' => 'Découvrez les logements et villas proposés par GELPAZ IMMO à Ouagadougou et au Burkina Faso.',
        'property' => 'Découvrez les détails de cette propriété GELPAZ IMMO.',
        'blog' => 'Les actualités et conseils immobiliers de GELPAZ IMMO.',
        'post' => 'Actualité immobilière et conseils GELPAZ IMMO.',
        'contact' => 'Contactez GELPAZ IMMO à Ouagadougou pour votre projet immobilier.',
    ];
    return $descriptions[$page] ?? $descriptions['home'];
}
?>

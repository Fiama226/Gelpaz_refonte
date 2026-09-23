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
    'article' => 'post',
    'contact-us' => 'contact',
    'mentions-legales' => 'legal',
    'politique-de-confidentialite' => 'legal',
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
if (str_starts_with($path, 'article/')) {
    $route_page = 'post';
    $_GET['slug'] = basename($path);
}
if ($route_page === 'properties' && $path === 'property_action_category/vente') {
    $_GET['filter'] = 'vente';
}
$allowed_pages = ['home', 'about', 'services', 'properties', 'property', 'blog', 'post', 'contact', 'team', 'faq', 'pricing', 'legal'];
$requested_page = (string) ($_GET['page'] ?? $route_page ?? 'home');
$is_404 = !in_array($requested_page, $allowed_pages, true);
if ($is_404) {
    http_response_code(404);
}

$post_slug = trim((string) ($_GET['slug'] ?? ''));
$selected_post = $post_slug !== '' ? post_by_slug($post_slug) : $posts[0];
if ($post_slug !== '' && $selected_post === null) {
    $is_404 = true;
    http_response_code(404);
    $current_page = '404';
}
$current_page = $is_404 ? '404' : $requested_page;
$GLOBALS['current_page'] = $current_page;

$property_id = $_GET['id'] ?? 'modele-f4c';
$selected_property = property_by_id($property_id) ?: $properties[0];
$property_filter = strtolower(trim((string) ($_GET['filter'] ?? 'all')));
$property_location = strtolower(trim((string) ($_GET['location'] ?? 'all')));
$property_beds = trim((string) ($_GET['beds'] ?? 'all'));
$allowed_filters = ['all', 'vente', 'location'];
$allowed_locations = ['all', 'centre', 'ouagadougou', 'bassinko'];
$allowed_beds = ['all', '2', '3', '4'];
$property_filter = in_array($property_filter, $allowed_filters, true) ? $property_filter : 'all';
$property_location = in_array($property_location, $allowed_locations, true) ? $property_location : 'all';
$property_beds = in_array($property_beds, $allowed_beds, true) ? $property_beds : 'all';
$has_property_search = $property_filter !== 'all' || $property_location !== 'all' || $property_beds !== 'all';
$property_sort = in_array($_GET['sort'] ?? 'recent', ['recent', 'area'], true) ? $_GET['sort'] : 'recent';
$visible_properties = array_values(array_filter($properties, static function (array $property) use ($property_filter, $property_location, $property_beds): bool {
    $location = strtolower($property['location']);
    $matches_filter = $property_filter === 'all' || strtolower($property['category']) === $property_filter;
    $matches_location = $property_location === 'all' || str_contains($location, $property_location);
    $matches_beds = $property_beds === 'all' || (int) $property['beds'] === (int) $property_beds;
    return $matches_filter && $matches_location && $matches_beds;
}));
if ($visible_properties === [] && !$has_property_search) {
    $visible_properties = $properties;
    $property_filter = 'all';
}
if ($property_sort === 'area') {
    usort($visible_properties, static fn (array $a, array $b): int => (int) preg_replace('/\D+/', '', (string) ($a['area'] ?? '0')) <=> (int) preg_replace('/\D+/', '', (string) ($b['area'] ?? '0')));
}
$contact_state = null;
$contact_values = ['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'message' => ''];
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $current_page === 'contact') {
    foreach ($contact_values as $field => $default) {
        $contact_values[$field] = trim((string) ($_POST[$field] ?? ''));
    }
    $consent = ($_POST['consent'] ?? '') === 'yes';
    $honeypot = trim((string) ($_POST['website'] ?? ''));
    if ($honeypot !== '') {
        $contact_state = 'success'; // Bot submissions are dropped silently.
    } elseif ($contact_values['email'] === '' || $contact_values['message'] === '' || !$consent
        || !filter_var($contact_values['email'], FILTER_VALIDATE_EMAIL)) {
        $contact_state = 'error';
    } else {
        $name = ($contact_values['first_name'] . ' ' . $contact_values['last_name']) ?: 'Visiteur du site';
        $body = "Nom : {$name}\n"
            . "E-mail : {$contact_values['email']}\n"
            . "Téléphone : {$contact_values['phone']}\n\n"
            . "Message :\n{$contact_values['message']}\n";
        $headers = "From: GELPAZ IMMO <infos@gelpaz.com>\r\n"
            . 'Reply-To: ' . $contact_values['email'] . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n";
        $sent = @mail('infos@gelpaz.com', 'Nouveau message du site GELPAZ IMMO — ' . $name, $body, $headers);
        $contact_state = $sent ? 'success' : 'error';
    }
}

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
        'team' => 'Rencontrez l’équipe GELPAZ IMMO : des conseillers à votre écoute pour tous vos projets immobiliers au Burkina Faso.',
        'faq' => 'Réponses aux questions fréquentes sur l’achat, la location et la souscription de logements avec GELPAZ IMMO.',
        'pricing' => 'Découvrez les offres et les modalités de souscription logement proposées par GELPAZ IMMO.',
        'legal' => 'Mentions légales, protection des données et politique de confidentialité du site GELPAZ IMMO.',
        '404' => 'La page demandée est introuvable. Retrouvez nos logements et notre équipe depuis l’accueil du site GELPAZ IMMO.',
    ];
    return $descriptions[$page] ?? $descriptions['home'];
}
?>

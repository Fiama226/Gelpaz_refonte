<?php
/**
 * Content used by the presentation layer. Images intentionally keep the source
 * URLs from gelpaz.com so that property information stays aligned with the
 * published catalogue.
 */
$site = [
    'name' => 'GELPAZ IMMO',
    'tagline' => 'La différence',
    'phone' => '+226 25 37 10 55',
    'whatsapp' => '+226 67 30 81 85',
    'email' => 'infos@gelpaz.com',
    'address' => 'DAGNOEN, rue 29.128, Ouagadougou, Burkina Faso',
    'hours' => '8h–13h · 14h–17h',
];

$images = [
    'hero' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-525x328.jpg',
    'hero_alt' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-525x328.jpg',
    'villa' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-525x328.jpg',
    'news_one' => 'https://gelpaz.com/wp-content/uploads/2026/09/799869148_1056208587167745_1492735262498320328_n-525x328.jpg',
    'news_two' => 'https://gelpaz.com/wp-content/uploads/2026/09/801010406_1741640150501365_7041552529840250891_n-525x328.jpg',
    'news_three' => 'https://gelpaz.com/wp-content/uploads/2026/08/arton122698-517b6-6c4ae-525x328.jpg',
    'partner_one' => 'https://gelpaz.com/wp-content/uploads/2023/08/telechargement-2.png',
    'partner_two' => 'https://gelpaz.com/wp-content/uploads/2023/08/WhatsApp-Image-2023-08-08-at-15.30.25-3.jpeg',
    'partner_three' => 'https://gelpaz.com/wp-content/uploads/2023/08/Capture.png',
    'partner_four' => 'https://gelpaz.com/wp-content/uploads/2025/09/telecharge-3.jpg',
];

$hero_slides = [
    ['image' => $images['hero_alt'], 'alt' => 'Résidence proposée par GELPAZ IMMO'],
    ['image' => $images['hero'], 'alt' => 'Logement familial proposé par GELPAZ IMMO'],
    ['image' => $images['villa'], 'alt' => 'Villa proposée par GELPAZ IMMO'],
];

$properties = [
    [
        'id' => 'modele-f4c',
        'title' => 'F4 Moyen standing',
        'model' => 'F4C',
        'location' => 'Centre, Ouagadougou',
        'status' => 'Disponible',
        'category' => 'Vente',
        'price' => 'Sur demande',
        'area' => '145 m²',
        'beds' => '03',
        'baths' => '03',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-525x328.jpg',
        'description' => 'Un logement familial pensé pour conjuguer confort, fonctionnalité et qualité de vie au quotidien.',
    ],
    [
        'id' => 'modele-f3a',
        'title' => 'F3 Moyen standing',
        'model' => 'F3A',
        'location' => 'Centre, Ouagadougou',
        'status' => 'Disponible',
        'category' => 'Vente',
        'price' => 'Sur demande',
        'area' => '130 m²',
        'beds' => '02',
        'baths' => '02',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-525x328.jpg',
        'description' => 'Des volumes lumineux et une organisation intelligente pour une vie simple et agréable.',
    ],
    [
        'id' => 'modele-f4b',
        'title' => 'F4 Moyen standing',
        'model' => 'F4B',
        'location' => 'Centre, Ouagadougou',
        'status' => 'Disponible',
        'category' => 'Vente',
        'price' => 'Sur demande',
        'area' => '145 m²',
        'beds' => '03',
        'baths' => '03',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-525x328.jpg',
        'description' => 'Un modèle élégant et contemporain, idéal pour accueillir votre famille dans un cadre serein.',
    ],
    [
        'id' => 'modele-f4a',
        'title' => 'F4 Moyen standing',
        'model' => 'F4A',
        'location' => 'Ouagadougou',
        'status' => 'Disponible',
        'category' => 'Vente',
        'price' => 'Sur demande',
        'area' => '145 m²',
        'beds' => '03',
        'baths' => '03',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-525x328.jpg',
        'description' => 'Une résidence conçue pour durer, avec des espaces généreux et des finitions soignées.',
    ],
    [
        'id' => 'f5-haut-standing',
        'title' => 'F5 haut standing',
        'model' => 'F5',
        'location' => 'Centre, Ouagadougou',
        'status' => 'Disponible',
        'category' => 'Vente',
        'price' => '120.000.000 XOF',
        'area' => '555 m²',
        'beds' => '04',
        'baths' => '03',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-525x328.jpg',
        'description' => 'Entrez dans un univers de raffinement avec cette propriété haut standing de plus de 555 m².',
    ],
    [
        'id' => 'villa-bassinko',
        'title' => 'Villa de Bassinko',
        'model' => 'F3 Moyen Standing',
        'location' => 'Bassinko, Centre',
        'status' => 'Immédiatement disponible',
        'category' => 'Location',
        'price' => '40.000 XOF',
        'area' => '250 m²',
        'beds' => '02',
        'baths' => '01',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-525x328.jpg',
        'description' => 'Une villa fonctionnelle et accueillante dans un environnement résidentiel à Bassinko.',
    ],
];

$posts = [
    [
        'title' => 'Restitution des travaux de validation des plans de développement urbain',
        'date' => '09 septembre 2026',
        'image' => $images['news_one'],
        'excerpt' => 'Le Ministre de l’Aménagement du Territoire, de l’Urbanisme et de l’Habitat a accordé une audience aux acteurs du secteur.',
    ],
    [
        'title' => 'Signature du PV de validation des projets de développement urbain',
        'date' => '08 septembre 2026',
        'image' => $images['news_two'],
        'excerpt' => 'Une étape importante pour accompagner le développement urbain et l’accès à un habitat de qualité au Burkina Faso.',
    ],
    [
        'title' => 'Promotion immobilière : 40 sociétés agréées au Burkina Faso',
        'date' => '17 août 2026',
        'image' => $images['news_three'],
        'excerpt' => 'Le secteur de la promotion immobilière poursuit sa structuration et ouvre de nouvelles opportunités.',
    ],
    [
        'title' => 'Les tendances du marché immobilier à Ouagadougou',
        'date' => '04 mars 2023',
        'image' => $images['hero_alt'],
        'excerpt' => 'Comprendre les tendances du marché pour prendre des décisions éclairées et investir sereinement.',
    ],
    [
        'title' => 'Construire un patrimoine immobilier durable',
        'date' => '18 février 2023',
        'image' => $images['villa'],
        'excerpt' => 'Les bons réflexes pour inscrire votre projet immobilier dans le temps.',
    ],
    [
        'title' => 'Une nouvelle victoire pour Gelpaz Immo',
        'date' => '13 février 2025',
        'image' => $images['hero'],
        'excerpt' => 'GELPAZ IMMO sacrée meilleure entreprise de promotion immobilière du Burkina Faso.',
    ],
];

$services = [
    ['icon' => '⌂', 'title' => 'Vente de propriétés', 'text' => 'Trouvez un logement adapté à votre quotidien, à votre projet et à votre budget.'],
    ['icon' => '↗', 'title' => 'Location', 'text' => 'Identifiez rapidement un cadre de vie agréable avec des informations claires à chaque étape.'],
    ['icon' => '◈', 'title' => 'Gestion immobilière', 'text' => 'Valorisez, administrez et sécurisez votre patrimoine avec un interlocuteur fiable.'],
    ['icon' => '⌕', 'title' => 'Conseil & expertise', 'text' => 'Appuyez vos décisions sur notre connaissance du marché immobilier burkinabè.'],
];

$testimonials = [
    ['name' => 'Issa KINI', 'role' => "Résident à la cité de l'intégration depuis 2021", 'text' => "Je suis très satisfait des services qui m'ont été proposés à GELPAZ IMMO. Le site est très agréable à vivre et je n’ai eu aucune difficulté majeure."],
    ['name' => 'Ousseni YAMEOGO', 'role' => "Résident à la cité de l'intégration", 'text' => "Acquérir une maison n’est pas facile au Burkina. Mais avec GELPAZ IMMO, toutes les étapes ont été facilitées. Je suis satisfait en tout cas."],
    ['name' => 'Margerite KAMBOU', 'role' => "Résidente à la cité de l'intégration depuis décembre 2022", 'text' => 'Je suis satisfaite à 100 %. La promesse de viabilisation a été tenue. Je recommande GELPAZ IMMO !'],
];

$team = [
    ['name' => 'L’équipe Gelpaz', 'role' => 'Conseil immobilier', 'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=700&q=85'],
    ['name' => 'Nos conseillers', 'role' => 'Accompagnement client', 'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=700&q=85'],
    ['name' => 'Nos experts', 'role' => 'Expertise immobilière', 'image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=700&q=85'],
];

$faqs = [
    ['q' => 'Comment acheter une propriété avec GELPAZ IMMO ?', 'a' => 'Parcourez nos offres, choisissez la propriété qui vous intéresse puis contactez notre service commercial par téléphone, WhatsApp ou e-mail.'],
    ['q' => 'Quels types de biens proposez-vous ?', 'a' => 'Nous proposons des villas et logements de standing à la vente ou à la location, dans plusieurs zones de Ouagadougou et du Centre.'],
    ['q' => 'Puis-je visiter une propriété ?', 'a' => 'Oui. Après votre prise de contact, notre équipe organise un rendez-vous adapté à votre disponibilité.'],
    ['q' => 'Comment réserver un logement ?', 'a' => 'La réservation se fait avec le service commercial après lecture des informations disponibles et validation de votre projet.'],
    ['q' => 'Où êtes-vous situés ?', 'a' => 'Notre agence est située à DAGNOEN, rue 29.128, Ouagadougou, Burkina Faso.'],
    ['q' => 'Quels sont vos horaires ?', 'a' => 'Nous vous accueillons de 8h à 13h puis de 14h à 17h, du lundi au vendredi.'],
];

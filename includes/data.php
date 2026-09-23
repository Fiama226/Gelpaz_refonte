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
    'hero' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-835x467.jpg',
    'hero_alt' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-835x467.jpg',
    'villa' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-835x467.jpg',
    'news_one' => 'https://gelpaz.com/wp-content/uploads/2026/09/799869148_1056208587167745_1492735262498320328_n-835x467.jpg',
    'news_two' => 'https://gelpaz.com/wp-content/uploads/2026/09/801010406_1741640150501365_7041552529840250891_n-835x467.jpg',
    'news_three' => 'https://gelpaz.com/wp-content/uploads/2026/08/arton122698-517b6-6c4ae-835x467.jpg',
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
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-835x467.jpg',
        'description' => 'Un logement familial pensé pour conjuguer confort, fonctionnalité et qualité de vie au quotidien.',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0088-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0089-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0090-1-835x467.jpg',
        ],
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
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-835x467.jpg',
        'description' => 'Des volumes lumineux et une organisation intelligente pour une vie simple et agréable.',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0091-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0092-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0093-1-835x467.jpg',
        ],
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
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-835x467.jpg',
        'description' => 'Un modèle élégant et contemporain, idéal pour accueillir votre famille dans un cadre serein.',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0095-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0096-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-2-835x467.jpg',
        ],
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
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-835x467.jpg',
        'description' => 'Une résidence conçue pour durer, avec des espaces généreux et des finitions soignées.',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0087-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0089-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0091-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0093-1-835x467.jpg',
        ],
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
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-835x467.jpg',
        'description' => 'Entrez dans un univers de raffinement avec cette propriété haut standing de plus de 555 m².',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0079-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0092-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0094-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0096-1-835x467.jpg',
        ],
    ],
    [
        'id' => 'villa-bassinko',
        'title' => 'Villa de Bassinko',
        'model' => 'F3 Moyen Standing',
        'location' => 'Bassinko, Centre',
        'status' => 'Immédiatement disponible',
        'category' => 'Location',
        'price' => '40.000 XOF / mois',
        'area' => '250 m²',
        'beds' => '02',
        'baths' => '01',
        'image' => 'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-835x467.jpg',
        'description' => 'Une villa fonctionnelle et accueillante dans un environnement résidentiel à Bassinko.',
        'gallery' => [
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0052-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0090-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0093-1-835x467.jpg',
            'https://gelpaz.com/wp-content/uploads/2026/08/IMG-20250813-WA0096-1-835x467.jpg',
        ],
    ],
];

$posts = [
    [
        'slug' => 'restitution-plans-developpement-urbain',
        'title' => 'Restitution des travaux de validation des plans de développement urbain',
        'date' => '09 septembre 2026',
        'iso' => '2026-09-09',
        'image' => $images['news_one'],
        'excerpt' => 'Le Ministre de l’Aménagement du Territoire, de l’Urbanisme et de l’Habitat a accordé une audience aux acteurs du secteur.',
        'content' => [
            ['h' => 'Une étape de plus pour l’urbanisme burkinabè', 'p' => 'Le Ministère de l’Aménagement du Territoire, de l’Urbanisme et de l’Habitat a réuni les acteurs publics et privés du secteur pour restituer les travaux de validation des plans de développement urbain. Ces documents fixent le cadre dans lequel les projets immobiliers pourront se déployer dans les prochaines années.'],
            ['h' => 'Ce que cela change pour les porteurs de projets', 'p' => 'Pour un promoteur comme GELPAZ IMMO, disposer d’un cadre de planification clair simplifie la préparation des dossiers, la sécurisation du foncier et la programmation des travaux. C’est une condition essentielle pour livrer des logements de qualité, dans les délais annoncés et à des prix maîtrisés.'],
            ['h' => 'La suite', 'p' => 'Nous continuerons de suivre ces travaux et d’y contribuer, afin que les projets de nos clients s’inscrivent dans une planification urbaine cohérente et durable.'],
        ],
    ],
    [
        'slug' => 'signature-pv-developpement-urbain',
        'title' => 'Signature du PV de validation des projets de développement urbain',
        'date' => '08 septembre 2026',
        'iso' => '2026-09-08',
        'image' => $images['news_two'],
        'excerpt' => 'Une étape importante pour accompagner le développement urbain et l’accès à un habitat de qualité au Burkina Faso.',
        'content' => [
            ['h' => 'Un cadre validé', 'p' => 'La signature du procès-verbal de validation marque l’aboutissement d’un travail de concertation entre les services de l’État et les professionnels du secteur immobilier.'],
            ['h' => 'Un habitat plus accessible', 'p' => 'Cette validation crée les conditions d’un développement plus ordonné des zones d’habitation. Elle permet d’anticiper les besoins en voiries, en réseaux et en équipements, et donc de proposer aux familles des quartiers viables sur le long terme.'],
            ['h' => 'Notre engagement', 'p' => 'GELPAZ IMMO s’engage à aligner ses programmes sur ces orientations et à poursuivre ses projets avec la même exigence de qualité et de transparence.'],
        ],
    ],
    [
        'slug' => 'promotion-immobiliere-40-societes-agreees',
        'title' => 'Promotion immobilière : 40 sociétés agréées au Burkina Faso',
        'date' => '17 août 2026',
        'iso' => '2026-08-17',
        'image' => $images['news_three'],
        'excerpt' => 'Le secteur de la promotion immobilière poursuit sa structuration et ouvre de nouvelles opportunités.',
        'content' => [
            ['h' => 'Un secteur qui se structure', 'p' => 'Le nombre de sociétés de promotion immobilière agréées confirme la dynamique du marché burkinabè. Cette structuration progressive protège mieux les acquéreurs et donne davantage de lisibilité aux projets.'],
            ['h' => 'Ce qu’un agrément garantit', 'p' => 'Un promoteur agréé justifie de capacités techniques et financières, d’un cadre juridique et de programmes identifiés. Pour un client, c’est un premier niveau de sécurité avant même la signature.'],
            ['h' => 'Comment choisir son promoteur', 'p' => 'Au-delà de l’agrément, vérifiez la disponibilité des titres, l’avancement réel des travaux et la clarté des échéances de livraison. Notre équipe accompagne ses clients sur chacun de ces points.'],
        ],
    ],
    [
        'slug' => 'tendances-marche-immobilier-ouagadougou',
        'title' => 'Les tendances du marché immobilier à Ouagadougou',
        'date' => '04 mars 2023',
        'iso' => '2023-03-04',
        'image' => $images['hero_alt'],
        'excerpt' => 'Comprendre les tendances du marché pour prendre des décisions éclairées et investir sereinement.',
        'content' => [
            ['h' => 'Une demande portée par la croissance urbaine', 'p' => 'Ouagadougou continue de s’étendre et la demande de logements reste forte, notamment pour les formats familiaux de 3 et 4 pièces dans les zones bien desservies.'],
            ['h' => 'Des attentes de qualité', 'p' => 'Les acquéreurs recherchent désormais des finitions soignées, une bonne exposition, la présence de terrasses et une sécurité de quartier. La qualité de construction devient un critère de décision aussi important que le prix.'],
            ['h' => 'Ce que cela implique', 'p' => 'Les programmes les mieux positionnés sont ceux qui combinent emplacement cohérent, prix transparent et suivi de chantier rigoureux. C’est la logique que nous appliquons dans nos projets.'],
        ],
    ],
    [
        'slug' => 'construire-patrimoine-immobilier-durable',
        'title' => 'Construire un patrimoine immobilier durable',
        'date' => '18 février 2023',
        'iso' => '2023-02-18',
        'image' => $images['villa'],
        'excerpt' => 'Les bons réflexes pour inscrire votre projet immobilier dans le temps.',
        'content' => [
            ['h' => 'Investir dans l’immobilier au Burkina Faso', 'p' => 'Construire un patrimoine immobilier est une décision importante. Chez GELPAZ IMMO, nous croyons qu’un projet réussi commence par une compréhension claire de vos objectifs et du marché.'],
            ['h' => 'Comprendre les fondamentaux', 'p' => 'Choisir un bien, c’est aussi choisir un environnement, un horizon et une manière de vivre. Notre équipe vous aide à regarder au-delà de la transaction pour trouver une solution réellement adaptée.'],
            ['h' => 'L’importance d’un accompagnement local', 'p' => 'Notre connaissance de Ouagadougou et des réalités du Burkina Faso nous permet de vous orienter avec pragmatisme, transparence et sérénité, de la première visite à la remise des clés.'],
        ],
    ],
    [
        'slug' => 'nouvelle-victoire-gelpaz-immo',
        'title' => 'Une nouvelle victoire pour Gelpaz Immo',
        'date' => '13 février 2025',
        'iso' => '2025-02-13',
        'image' => $images['hero'],
        'excerpt' => 'GELPAZ IMMO sacrée meilleure entreprise de promotion immobilière du Burkina Faso.',
        'content' => [
            ['h' => 'Une reconnaissance du secteur', 'p' => 'Ce prix récompense un travail collectif : celui d’une équipe qui privilégie l’écoute du client, la rigueur des chantiers et la transparence des engagements.'],
            ['h' => 'Ce que cela change pour nos clients', 'p' => 'Cette distinction conforte la confiance de nos acquéreurs et renforce notre exigence. Elle nous oblige : chaque programme doit désormais être à la hauteur de ce titre.'],
            ['h' => 'Merci', 'p' => 'Merci à nos clients, à nos partenaires et à nos équipes. Rendez-vous sur nos projets en cours pour la suite de l’aventure.'],
        ],
    ],
];

$services = [
    ['icon' => 'home', 'title' => 'Vente de propriétés', 'text' => 'Trouvez un logement adapté à votre quotidien, à votre projet et à votre budget.'],
    ['icon' => 'key', 'title' => 'Location', 'text' => 'Identifiez rapidement un cadre de vie agréable avec des informations claires à chaque étape.'],
    ['icon' => 'shield', 'title' => 'Gestion immobilière', 'text' => 'Valorisez, administrez et sécurisez votre patrimoine avec un interlocuteur fiable.'],
    ['icon' => 'message', 'title' => 'Conseil & expertise', 'text' => 'Appuyez vos décisions sur notre connaissance du marché immobilier burkinabè.'],
];

$testimonials = [
    ['name' => 'Issa KINI', 'role' => "Résident à la cité de l'intégration depuis 2021", 'text' => "Je suis très satisfait des services qui m'ont été proposés à GELPAZ IMMO. Le site est très agréable à vivre et je n’ai eu aucune difficulté majeure."],
    ['name' => 'Ousseni YAMEOGO', 'role' => "Résident à la cité de l'intégration", 'text' => "Acquérir une maison n’est pas facile au Burkina. Mais avec GELPAZ IMMO, toutes les étapes ont été facilitées. Je suis satisfait en tout cas."],
    ['name' => 'Margerite KAMBOU', 'role' => "Résidente à la cité de l'intégration depuis décembre 2022", 'text' => 'Je suis satisfaite à 100 %. La promesse de viabilisation a été tenue. Je recommande GELPAZ IMMO !'],
];

// "photo" attend le portrait réel du collaborateur. Tant qu'il est vide, la carte
// affiche un monogramme de marque : mieux vaut aucune photo qu'un visage d'emprunt.
$team = [
    ['name' => 'L’équipe Gelpaz', 'role' => 'Conseil immobilier', 'photo' => '', 'photo_alt' => ''],
    ['name' => 'Nos conseillers', 'role' => 'Accompagnement client', 'photo' => '', 'photo_alt' => ''],
    ['name' => 'Nos experts', 'role' => 'Expertise immobilière', 'photo' => '', 'photo_alt' => ''],
];

$faqs = [
    ['q' => 'Comment acheter une propriété avec GELPAZ IMMO ?', 'a' => 'Parcourez nos offres, choisissez la propriété qui vous intéresse puis contactez notre service commercial par téléphone, WhatsApp ou e-mail.'],
    ['q' => 'Quels types de biens proposez-vous ?', 'a' => 'Nous proposons des villas et logements de standing à la vente ou à la location, dans plusieurs zones de Ouagadougou et du Centre.'],
    ['q' => 'Puis-je visiter une propriété ?', 'a' => 'Oui. Après votre prise de contact, notre équipe organise un rendez-vous adapté à votre disponibilité.'],
    ['q' => 'Comment réserver un logement ?', 'a' => 'La réservation se fait avec le service commercial après lecture des informations disponibles et validation de votre projet.'],
    ['q' => 'Où êtes-vous situés ?', 'a' => 'Notre agence est située à DAGNOEN, rue 29.128, Ouagadougou, Burkina Faso.'],
    ['q' => 'Quels sont vos horaires ?', 'a' => 'Nous vous accueillons de 8h à 13h puis de 14h à 17h, du lundi au vendredi.'],
];

# GELPAZ IMMO · Refonte PHP

Reconstruction responsive de GELPAZ IMMO, inspirée par les références visuelles du kit de référence (`docs/reference/nistora-kit.zip`) et alimentée par les informations publiques de [gelpaz.com](https://gelpaz.com/).

## Lancer le site

Le site est une application PHP sans framework :

```bash
php -S 0.0.0.0:8000
```

Puis ouvrir `http://localhost:8000`.

## Routes disponibles

`page_url()` génère les URLs propres (les mêmes chemins que le site d’origine, réécrits vers `index.php` par `.htaccess`) :

- `/` — page d’accueil
- `/nous-connaitre` — à propos, histoire, valeurs et équipe
- `/nos-activites` — activités et processus
- `/nos-offres-immobilieres` — catalogue des logements (`?filter=vente|location`, `?location=`, `?beds=`, `?sort=`)
- `/estate_property/modele-f4c` — détail d’un logement (galerie photo + carte)
- `/blog-list-no-sidebar-2` — actualités
- `/article/<slug>` — article détaillé
- `/contact-us` — formulaire et coordonnées
- `/mentions-legales` — mentions légales et politique de confidentialité
- `/team`, `/faq`, `/souscription-logement` — pages complémentaires du kit de références
- `/sitemap.xml` — plan du site généré depuis le contenu réel

Les URLs historiques (`index.php?page=...`, `property_action_category/vente`, `property_area/centre`, …) restent acceptées pour la compatibilité. Une page inconnue renvoie maintenant une vraie page 404 (statut HTTP 404) au lieu de l’accueil.

## Organisation

- `index.php` : front controller, document shell, balises SEO (canonical, Open Graph, JSON-LD `RealEstateAgent`) et préchargement de l’image LCP
- `pages/*.php` : vues PHP dédiées pour chaque page du site
- `includes/bootstrap.php` : routage, état de page, traitement du formulaire de contact
- `includes/data.php` : contenu Gelpaz, catalogue de logements, articles et équipe
- `includes/functions.php` : helpers, sprite d’icônes SVG, images responsives, composants de cartes
- `includes/layout.php` : header, footer, heroes et CTA communs
- `assets/css/style.css` : design system responsive (tokens d’espacement `--space-*`, blocs `v2`/`v3` en fin de fichier)
- `assets/js/app.js` : menu mobile, diaporama, header collant, visionneuse photo, retour en haut
- `assets/images/` : `logo.png`, `logo-white.png`, favicons et icône Apple
- `sitemap.php`, `robots.txt` : indexation
- `tools/fetch-media.sh` : rapatriement des visuels pour l’auto-hébergement

## Aperçu visuel sans PHP

`docs/preview/ui-preview.html` est généré depuis les sources réelles (`includes/layout.php`, `includes/functions.php`) et permet de vérifier le header, le jeu d’icônes, les cartes de logements, la galerie + carte, les états de formulaire et le footer dans un navigateur, sans exécuter PHP :

```bash
python3 -m http.server 8000 --bind 0.0.0.0
# puis ouvrir /docs/preview/ui-preview.html
```

Régénérer après une modification du header/footer : `python3 screenshots_review/make_preview.py` (script d’outillage hors dépôt de production).

`docs/preview/` contient en plus **l’intégralité du site en statique** (24 pages : accueil, logements + filtre location, 6 fiches, actualités, 6 articles, à propos, activités, équipe, tarifs, FAQ, contact, mentions légales, 404), rendues depuis les gabarits PHP réels et reliées entre elles par une barre de navigation d’aperçu :

```bash
python3 tools/static-snapshot.py      # regénère docs/preview/*.html
```

Le script s’arrête net (`SnapshotError`) sur toute construction PHP qu’il ne sait pas interpréter : aucune page ne peut être publiée tronquée à son insu. Ouvrir `docs/preview/index.html` pour la revue complète ; détails dans `docs/preview/README.md`.

## Audit UX/UI

`UX-UI-AUDIT.md` documente l’audit complet (42 constats, priorités P0/P1/P2) et l’état d’avancement.

### Itération 1 (P0)

- Header : logo recentré et dimensionné par hauteur, variante blanche dédiée, soulignements de navigation ancrés au texte, menus déroulants à `calc(100% + 14px)`, états actifs sur « À propos » / « Nos offres », header collant après 90 px de défilement.
- Icônes : un seul jeu SVG (sprite unique) remplace tous les glyphes Unicode.
- Typographie : plancher à 12 px pour les libellés, 13–15 px pour les textes, 16 px pour les champs (évite le zoom iOS).
- Contraste : gris corrigés (dates, rôles, eyebrows, numéros d’étapes) pour repasser les seuils WCAG AA mesurés.
- Mobile : plus de chevauchement entre les contrôles du diaporama et l’indicateur de défilement.
- Conversion : formulaire de contact fonctionnel (honeypot, états succès/erreur, valeurs conservées), bouton WhatsApp flottant, CTA WhatsApp pré-rempli sur chaque carte, horaires affichés.
- SEO/perf : favicons, Open Graph, canonical, JSON-LD, `robots`/cache/compression/en-têtes de sécurité, kit de référence retiré de la racine publique, lien d’évitement clavier.

### Itération 2 (P1)

- Visuels : variantes WordPress **835 px** au lieu des vignettes 525 px, avec `srcset` (835/525), repli automatique, `width`/`height` (anti-CLS) et `decoding="async"`.
- Galeries : chaque logement affiche de **vraies photos** de son dossier avec visionneuse accessible (Échap, flèches, retour du focus).
- Actualités : **un article par page** (`/article/<slug>`) avec contenu rédigé, date ISO, partage WhatsApp/e-mail, articles récents et « à lire aussi » distincts.
- Légal : **mentions légales** et **politique de confidentialité**, liées depuis le pied de page et la case de consentement du formulaire.
- Cartes : intégration **OpenStreetMap** (sans clé d’API) sur la page contact et la fiche logement.
- SEO : `robots.txt` et sitemap généré depuis les pages, logements et articles réels.

### Itération 3 (P2)

- **Équipe** : les portraits de banque d’images sont remplacés par des monogrammes de marque via `render_team_card()` ; renseigner `photo` dans `$team` (includes/data.php) suffit pour basculer sur les vrais portraits.
- **Performance** : `asset_version()` ajoute `?v=<filemtime>` à `style.css` et `app.js`, servis avec un cache d’un an (`immutable`) ; préchargement (`preload` + `imagesrcset`) de l’image du premier slide en page d’accueil.
- **Design system** : échelle d’espacement `--space-1 … --space-9` appliquée au rythme de sections et aux principaux espacements.
- **Accessibilité** : la région `aria-live` du diaporama ne s’exprime plus à chaque rotation ; l’annonce (« Image 2 sur 3 ») n’a lieu que sur action de l’utilisateur.

### Itération 4 (polish + aperçu de revue)

- **Finitions** : grille des témoignages sans cellule vide, visionneuse avec piège de focus + arrière-plan `inert` + verrou de défilement, feuille d’impression (`@media print`), recherche du hero compactée sous 560 px, première carte d’article mise en avant.
- **Défauts trouvés grâce à l’aperçu statique** : `canonical` des articles qui pointaient tous vers `/article`, `<title>`/`description` génériques sur les six articles (désormais tirés de l’article), modificateur `property-card--featured` inutile.
- **Revue** : les **24 pages** sont générées en statique dans `docs/preview/` avec une barre de navigation d’aperçu, pour relire toute la refonte avant fusion.

### Reste à faire (P3 / dépend du client)

- Renseigner les vrais portraits et noms des conseillers, ainsi que des logos de partenaires authentiques.
- Auto-héberger les visuels + conversion WebP/AVIF : `tools/fetch-media.sh` rapatrie les fichiers sur un poste connecté, puis remplacer `$images` par des chemins locaux.
- Minification/versionnage automatisé des assets dans une étape de build (le CSS/JS reste lisible en dépôt) et extension éventuelle de la visionneuse aux visuels d’articles.
- Contrôle visuel navigateur complet (Lighthouse, appareils réels) : l’environnement de développement de cette refonte ne dispose ni de PHP ni de navigateur.

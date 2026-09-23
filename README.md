# GELPAZ IMMO · Refonte PHP

Reconstruction responsive de GELPAZ IMMO, inspirée par les références visuelles du kit de référence (`docs/reference/nistora-kit.zip`) et alimentée par les informations publiques de [gelpaz.com](https://gelpaz.com/).

## Lancer le site

Le site est une application PHP sans framework :

```bash
php -S 0.0.0.0:8000
```

Puis ouvrir `http://localhost:8000`.

## Routes disponibles

`page_url()` génère désormais les URLs propres (les mêmes chemins que le site d’origine, réécrits vers `index.php` par `.htaccess`) :

- `/` — page d’accueil
- `/nous-connaitre` — à propos, histoire, valeurs et équipe
- `/nos-activites` — activités et processus
- `/nos-offres-immobilieres` — catalogue des logements (`?filter=vente|location`, `?location=`, `?beds=`, `?sort=`)
- `/estate_property/modele-f4c` — détail d’une propriété
- `/blog-list-no-sidebar-2` — actualités
- `/article` — article détaillé
- `/contact-us` — formulaire et coordonnées
- `/team`, `/faq`, `/souscription-logement` — pages complémentaires du kit de références

Les URLs historiques (`index.php?page=...`, `property_action_category/vente`, `property_area/centre`, …) restent acceptées pour la compatibilité. Une page inconnue renvoie maintenant une vraie page 404 (statut HTTP 404) au lieu de l’accueil.

## Organisation

- `index.php` : front controller, document shell, balises SEO (canonical, Open Graph, JSON-LD `RealEstateAgent`)
- `pages/*.php` : vues PHP dédiées pour chaque page du site
- `includes/bootstrap.php` : routage, état de page, traitement du formulaire de contact
- `includes/data.php` : contenu Gelpaz et catalogue de propriétés
- `includes/functions.php` : helpers, sprite d’icônes SVG, composants de cartes
- `includes/layout.php` : header, footer, heroes et CTA communs
- `assets/css/style.css` : design system responsive (bloc `v2` en fin de fichier : icônes, header collant, formulaires, conversion)
- `assets/js/app.js` : menu mobile, slideshow (pause/reduced-motion), header collant, retour en haut
- `assets/images/` : `logo.png` (couleur), `logo-white.png` (variante blanche pour fonds sombres), favicons et icône Apple

## Aperçu visuel sans PHP

`docs/preview/ui-preview.html` est généré depuis les sources réelles (`includes/layout.php`, `includes/functions.php`) et permet de vérifier le header, le jeu d’icônes, les cartes de propriétés, les états de formulaire et le footer dans un navigateur, sans exécuter PHP :

```bash
python3 -m http.server 8000 --bind 0.0.0.0
# puis ouvrir /docs/preview/ui-preview.html
```

Régénérer après une modification du header/footer : `python3 screenshots_review/make_preview.py` (script d’outillage hors dépôt de production).

## Audit UX/UI

`UX-UI-AUDIT.md` documente l’audit complet (42 constats, priorités P0/P1/P2) et l’état d’avancement de la mise en œuvre.

### Traité dans cette itération (P0 + une partie du P1)

- Header : logo recentré et dimensionné par hauteur (plus de `margin-top` compensatoire), variante blanche dédiée, soulignements de navigation ancrés au texte, menus déroulants à `calc(100% + 14px)`, états actifs sur « À propos » / « Nos offres », header collant après 90 px de défilement.
- Icônes : un seul jeu SVG (sprite unique) remplace tous les glyphes Unicode ; icônes de contact, WhatsApp et réseaux sociaux cohérentes.
- Typographie : plancher à 12 px pour les libellés, 13–15 px pour les textes, 16 px pour les champs de formulaire (évite le zoom iOS).
- Contraste : gris corrigés (dates, rôles, eyebrows, numéros d’étapes) pour repasser les seuils WCAG AA mesurés.
- Mobile : plus de chevauchement entre les contrôles du diaporama et l’indicateur de défilement ; menu mobile calé sur `calc(100% + 8px)`.
- Conversion : formulaire de contact fonctionnel (honeypot, états succès/erreur, champs conservés), bloc WhatsApp dans le footer, bouton WhatsApp flottant avec message pré-rempli, CTA WhatsApp pré-rempli sur chaque carte de propriété, horaires affichés.
- SEO/perf : favicons, Open Graph, canonical, JSON-LD, `robots`/cache/compression/en-têtes de sécurité dans `.htaccess`, kit de référence retiré de la racine publique (`docs/` bloqué), lien d’évitement clavier.

### Reste à faire (P1/P2)

- Remplacer les visuels distants par des images auto-hébergées en haute définition (le contenu utilise encore les URLs d’origine de gelpaz.com, conformément à l’objectif de fidélité).
- Photos/noms réels de l’équipe, logos de partenaires authentiques, articles de blog réellement distincts (routage par article).
- Carte interactive (Leaflet/OSM) à la place des visuels de carte CSS, mentions légales et politique de confidentialité, lightbox de galerie.
- Réorganisation complète de `style.css` (tokens d’espacement, reset en tête de fichier, minification et versionnage des assets pour des caches longs).

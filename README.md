# GELPAZ IMMO · Refonte PHP

Reconstruction responsive de GELPAZ IMMO, inspirée par les références visuelles de `screenshots.zip` et alimentée par les informations publiques de [gelpaz.com](https://gelpaz.com/).

## Lancer le site

Le site est une application PHP sans framework :

```bash
php -S 0.0.0.0:8000
```

Puis ouvrir `http://localhost:8000`.

## Routes disponibles

L’application utilise `index.php?page=...` afin de rester portable sur un hébergement PHP classique. Un fichier `.htaccess` permet aussi d’exposer les principales URLs de gelpaz.com lorsqu’Apache et `mod_rewrite` sont disponibles :

- `home` — page d’accueil
- `about` — à propos, histoire, valeurs et équipe
- `services` — activités et processus
- `properties` — catalogue des logements
- `property&id=modele-f4c` — détail d’une propriété
- `blog` — actualités
- `post` — article détaillé
- `contact` — formulaire et coordonnées
- `team`, `faq`, `pricing` — pages complémentaires du kit de références

## Organisation

- `index.php` : front controller et document shell
- `home.php`, `about.php`, `services.php`, etc. : points d’entrée PHP dédiés par page
- `pages/*.php` : vues PHP dédiées pour chaque page du site
- `includes/bootstrap.php` : routage, état de page et initialisation commune
- `includes/data.php` : contenu Gelpaz et catalogue de propriétés
- `includes/functions.php` : helpers et composants de cartes
- `includes/layout.php` : header, footer, heroes et CTA communs
- `assets/css/style.css` : design system responsive
- `assets/js/app.js` : menu mobile, FAQ, retour en haut et interactions simples
- `assets/images/logo.png` : logo Gelpaz détouré et optimisé localement

Les images immobilières utilisées dans le catalogue conservent leurs URLs publiques d’origine sur gelpaz.com, conformément à l’objectif de fidélité au contenu publié. Le logo est local pour assurer un affichage fiable et rapide.

# Aperçu statique — revue avant mise en ligne

Ce dossier contient une **copie statique navigable** de toute la refonte : les 24 pages sont
générées depuis les **vrais gabarits PHP** du dépôt (`pages/*.php`, `includes/layout.php`,
`includes/functions.php`), pas redessinées à la main. Il sert à relire l'ensemble du site dans un
navigateur, sans PHP ni serveur.

## Ouvrir l'aperçu

Ouvrir `index.html` (l'accueil de l'aperçu), puis naviguer avec la **barre noire en haut** de
chaque page : elle donne accès à toutes les pages de l'aperçu.

## Pages générées

| Page | Fichier |
| --- | --- |
| Accueil | `index.html` |
| Nos logements | `properties.html` |
| Logements (filtre location) | `properties-location.html` |
| Fiches logements (6) | `property-f4c.html`, `property-f3a.html`, `property-f4a.html`, `property-f4b.html`, `property-f5.html`, `property-bassinko.html` |
| Actualités | `blog.html` |
| Articles (6) | `article-plans-urbains.html`, `article-pv-urbain.html`, `article-40-societes.html`, `article-tendances.html`, `article-patrimoine.html`, `article-victoire.html` |
| À propos | `about.html` |
| Nos activités | `services.html` |
| Équipe | `team.html` |
| Tarifs | `pricing.html` |
| FAQ | `faq.html` |
| Contact | `contact.html` |
| Mentions légales / confidentialité | `legal.html` |
| Page 404 | `404.html` |
| Bibliothèque de composants | `ui-preview.html` (planches de styles générée séparément) |

## Regénérer après une modification de gabarit

```bash
python3 tools/static-snapshot.py      # écrit les 24 pages ici
```

Le script échoue volontairement (`SnapshotError`) dès qu'il rencontre une construction PHP qu'il
ne sait pas interpréter : un aperçu incomplet est ainsi impossible à confondre avec un aperçu
fidèle.

## Ce qui est simulé / hors sujet

- Les **images et polices** sont chargées depuis `gelpaz.com` et Google Fonts : l'aperçu a besoin
  d'une connexion, et les visuels définitifs dépendent de l'auto-hébergement (voir
  `tools/fetch-media.sh`).
- Le **formulaire de contact** et la **newsletter** ne sont pas envoyés (aucun serveur PHP).
- La barre noire en haut (`snap-bar`), son style et le `<meta name="robots" content="noindex">`
  sont **propres à l'aperçu** et n'existent pas dans le site livré.
- Les fichiers de ce dossier sont un **artefact de revue** : ils ne sont pas déployés en
  production et peuvent être supprimés une fois la relecture terminée.

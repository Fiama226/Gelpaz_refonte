> **Statut de mise en œuvre (itération 3).** **P0 (§1–8)** et **P1** sont livrés : header/logo recentrés, jeu d’icônes SVG unique, plancher typographique, contrastes WCAG corrigés, chevauchement mobile résolu, formulaire de contact fonctionnel (honeypot, états succès/erreur), WhatsApp (bouton flottant, CTA pré-remplis, bloc footer), favicons/Open Graph/canonical/JSON-LD, vraie page 404, URLs propres, `.htaccess` durci, visuels en 835 px avec `srcset` + repli, galeries réelles par bien, articles distincts par slug, mentions légales & confidentialité, cartes OpenStreetMap, `robots.txt` + sitemap généré.
>
> **P2 livré dans cette itération** : monogrammes d’équipe à la place des portraits d’emprunt, versionnage des assets (`?v=filemtime`) + cache long `immutable`, préchargement de l’image LCP, échelle d’espacement (`--space-*`) appliquée au rythme de sections, annonces d’accessibilité du diaporama limitées aux actions utilisateur.
>
> **Reste à faire** (dépend du client ou d’un poste connecté) : vrais portraits/noms de l’équipe, logos de partenaires authentiques, auto-hébergement + WebP/AVIF via `tools/fetch-media.sh`, minification/versionnage automatisé en build, contrôle Lighthouse sur navigateur réel. Aperçu statique : `docs/preview/ui-preview.html`.

**Scope:** Full review of the PHP refonte (`index.php`, `pages/*`, `includes/*`, `assets/css/style.css`, `assets/js/app.js`) against the visual reference kit in `screenshots.zip` (Nistora real-estate template) and current UX/UI best practice (WCAG 2.2, Core Web Vitals, real-estate conversion patterns).
**Date:** September 2026 · **Verdict:** The structural foundation is strong — the page architecture mirrors the reference kit well. What separates this from "premium agency" is **precision work**: optical alignment in the header, icon craft, type scale, contrast, and a handful of trust/flow gaps.

Each item below states the **Problem → Why it matters → Fix** (with code-level specifics). A prioritized roadmap closes the document.

---

## 0. Executive summary

| Area | Grade | Headline issue |
|---|---|---|
| Header & navigation | **D+** | Logo mis-centered, mixed icon systems, magic-number offsets (the flagged pain point) |
| Typography | B− | Beautiful display type; but 9–12px micro-text everywhere fails readability |
| Color & contrast | C | 6 text pairs fail WCAG AA (measured, §3) |
| Layout & spacing | B+ | Good rhythm; inconsistent spacing scale and a mobile overlap bug |
| Components | B | Solid cards/buttons; dead forms and placeholder "play" affordances |
| Iconography | **D** | Unicode glyphs (⌂ ↗ ◈ ⌕ ◔ ⌖) + SVG chevrons + CSS hamburger = 3 icon systems |
| Imagery | C− | 525px thumbnails upscaled into heroes; stock Unsplash team; fake partner logos |
| Accessibility | C+ | Good landmarks/focus; missing skip link, contrast, touch targets |
| UX & conversion | C | Contact form silently discards messages; prices lack context; no sticky CTA |
| Performance | C | Hotlinked third-party images, no caching, `screenshots.zip` in web root |
| SEO | C− | No favicon/OG/canonical/sitemap; all blog cards link to one page |

**Top 5 fixes (biggest visual/UX return per hour):**
1. Rebuild the header bar: center the logo properly, unify icons, kill magic numbers (§1).
2. Raise the type floor: nothing readable below 13px; labels ≥ 12px (§2).
3. Fix the 6 contrast failures (§3) — pure CSS, ~20 minutes.
4. Fix or hide the contact form — today it deletes inquiries (§10.1). This is costing real leads.
5. Replace Unicode glyph icons with one SVG icon set (§6).

---

## 1. Header & navigation — deep dive (the flagged issue: "icons are not well placed")

You're right: the header is the weakest 78px on the site. It's not one bug — it's **five alignment problems stacked**:

### 1.1 The logo is optically out of center
**Problem:** `.brand` has `margin-top:8px` (`style.css:64`), which deliberately pushes the logo *down* from the flex centerline — and the same rule mis-positions the footer logo too (`.brand--footer` only overrides width). Worse, `assets/images/logo.png` has **irregular transparent margins baked in** (empty regions bottom-left and right, swoosh hugging the top-left), so the visual mass of the mark sits low-right inside its box. The 8px nudge over-corrects one problem while creating another.
**Why it matters:** Human eyes detect misaligned logos instantly; it's the #1 signal of "template not finished."
**Fix:**
```css
/* 1. Crop the PNG's empty margins in an editor (tight bounding box + 2-4px padding),
      ideally export a monochrome-white SVG wordmark for dark headers. */
.brand{display:inline-flex;align-items:center}      /* delete margin-top:8px */
.brand img{height:40px;width:auto}                  /* optical height, not a width box */
.brand--footer img{height:36px}
```
Also stop faking white with `filter:brightness(0) invert(1) brightness(1.8) saturate(0)` — the double-brightness chain is fragile. Ship a proper white logo variant (or SVG with `currentColor`).

### 1.2 Three icon systems in one bar
**Problem:** The header mixes (a) an inline SVG chevron (`.nav-chevron`), (b) a text glyph `↗` inside the CTA, and (c) a hamburger made of three 1px `<span>`s. On the rest of the page, icons are Unicode symbols (`⌂ ◈ ⌕ ☎ ◔ ✉ ⌖`). The WhatsApp contact card even uses `◔` (a quarter-circle) — while the footer has a *correct* WhatsApp SVG. `.contact-icon--whatsapp{background:#168c54}` exists in CSS but is never applied in markup.
**Why it matters:** Mixed icon languages read as "assembled from scraps"; Unicode glyphs render differently on every OS (Windows/macOS/Android) and some (⌖ ⌁ ◔) are effectively invisible to users.
**Fix:** One inline SVG sprite (24×24 grid, 1.5–2px stroke, round caps — the footer WhatsApp path is already a good template). Use it for: nav chevrons, CTA arrows, hamburger, service icons, contact cards, amenities, socials. Delete the dead `.contact-icon*` rules.

### 1.3 Magic-number vertical rhythm
**Problem:** The underline for nav links is at `bottom:21px` while links have `padding:29px 0` — the underline floats 8px under the text but 21px above the bar's bottom edge, so it reads as detached. Dropdown menus use `top:64px` against a 78px-tall header and a ~71px-tall button — the panel's gap to the bar changes with font metrics.
**Why it matters:** Hover/active affordances must look welded to their label, and dropdowns must feel attached to their trigger.
**Fix:**
```css
.main-nav>a,.nav-dropdown>button{padding:8px 0}   /* let the 78px header own the height */
.main-nav>a:after,.nav-dropdown>button:after{bottom:0}          /* underline hugs the text box */
.nav-dropdown__menu{top:calc(100% + 14px)}        /* consistent gap below the whole trigger */
```
Same cleanup for the CTA: `.header-cta{margin-left:3px}` fights `.header-inner{gap:38px}` — remove the margin and let one gap token govern (e.g. `--space-6: 32px`). And `.button{gap:18px}` pushes the `↗` absurdly far from a 11px label in the compact header CTA — use `gap:8px` in the header variant (or drop the arrow; the reference CTA "Talk To An Agent" has none).

### 1.4 Active state missing on dropdown parents
**Problem:** `Accueil / Nos activités / Actualités / Contact` get `.is-active`, but **À propos** and **Nos offres** never highlight on their child pages (`includes/layout.php:15-27`). When you're on "Nos logements", the nav looks like nothing is selected.
**Fix:** Mark the parent button when `in_array($GLOBALS['current_page'], ['about','team'], true)` (and `['properties','property','pricing']`) and extend the underline rule to `.nav-dropdown>button.is-active:after`.

### 1.5 Header behavior & the post-page alignment bug
**Problems:**
- The header is `position:absolute` (hero) or `position:relative` (solid) — it scrolls away. On a lead-gen property site the "Parler à un conseiller" CTA should stay reachable (§10.3).
- On `pages/post.php` the header is rendered **inside** `.container`, then `render_header()` adds a *second* `.container` (`.header-inner`) — nested containers double-inset the brand/nav ~40px vs every other page. If you compared the post page with the home page side by side, the logo "jumps."
- Mobile hamburger: `.menu-toggle{width:40px;height:40px;padding:9px}` gives a 22px content box, but three 1px spans with 5px margins need ~23px — a 1px overflow shifts the icon's optical center; 1px strokes also look anemic next to 13px nav text.
**Fix:**
```php
/* pages/post.php — render the header outside .container, like every other page */
<section class="post-hero">
  <?php render_header(false); ?>
  <div class="container"> …hero copy… </div>
</section>
```
```css
.site-header{position:sticky;top:0;transition:background .25s,box-shadow .25s}
.site-header.is-scrolled{background:rgba(9,10,11,.92);backdrop-filter:blur(10px);box-shadow:0 8px 30px rgba(0,0,0,.18)}
.menu-toggle{display:grid;align-content:center;gap:6px}  /* balanced burger */
.menu-toggle span{height:2px;border-radius:2px}
```
(Sticky + overlay can coexist: keep `--overlay` absolute inside the hero, and reveal the sticky bar after scrolling past it — a small scroll listener in `app.js`.)

**Header target state:** cropped/centered logo (40px optical height) → 32px gap → nav with text-anchored underlines and one SVG chevron → 32px gap → single pill CTA. Chevron, arrow, burger all from the same icon family.

---

## 2. Typography & readability

**What works (keep):** The display system is genuinely good — `DM Sans` + `Playfair Display` italic accent word ("votre *avenir*"), tight `letter-spacing:-.04em` headings, `clamp()` fluid H1/H2, generous `line-height:1.6` body. This matches the reference kit's editorial tone.

**2.1 The 9–12px problem**
**Problem:** Roughly half the UI text is below readable size: eyebrows 10px, nav 12px (10px ≤900px!), buttons 12px, card copy 12px, search labels **9px**, contact labels 10px, meta/dates 9–11px, footer links 11px.
**Why it matters:** Below ~13px, body text fails comfort on desktop and is unusable on mobile; 9–10px labels fail outright. WCAG doesn't set a hard px minimum, but 200% zoom reflow (1.4.10) and readability research put the floor at 14–16px for body, 12–13px for labels.
**Fix (type scale tokens):**
```css
:root{
  --text-xs:12px;   /* meta, legal — never below */
  --text-sm:13px;   /* labels, nav secondary */
  --text-base:16px; /* body */
  --text-md:18px;   /* lead-in copy */
}
body{font-size:var(--text-base)}
.eyebrow{font-size:var(--text-xs)}          /* was 10px */
.main-nav>a{font-size:14px}                 /* was 12px, 10px on tablet! */
.button{font-size:14px}                     /* was 12px */
.property-search label{font-size:var(--text-sm)}  /* was 9px */
.contact-form label{font-size:var(--text-sm)}     /* was 10px */
.property-card__description,.service-card p{font-size:14px}
```

**2.2 Button & link label size**
12px bold all-caps-ish labels on pill buttons look timid next to 60px headlines. 14px/700 with `padding:15px 26px` will match the reference's confident pills.

**2.3 Detail polish**
- `.section-heading--center,.section-heading--center{text-align:center}` — duplicated selector (typo, harmless but signals unreviewed CSS).
- Two conflicting `.footer-socials a` rules (34px early in file, 24px later — later wins) and two conflicting `.contact-cards a>span` rules (44px vs 32px). Clean the cascade; the file currently resolves by "last one wins," which is how the icons ended up inconsistent in the first place.

---

## 3. Color & contrast (measured)

Palette is tasteful (near-black `#111316`, soft grey surfaces, brand blue `#0876d1`). Measured WCAG ratios for current pairs:

| Pair | Ratio | Required | Verdict |
|---|---|---|---|
| Post date `#92999e` on white | **2.89:1** | 4.5 | ✗ fail |
| Team role `#90979d` on white | **2.96:1** | 4.5 | ✗ fail |
| Card eyebrow-row `#8b9399` on white | **3.12:1** | 4.5 | ✗ fail |
| Eyebrow `#7b858d` on white | **3.76:1** | 4.5 | ✗ fail |
| Logo-strip wordmarks `#90989e` on `#f5f7f8` | **2.73:1** | 3 (large) | ✗ fail |
| Step numbers `#c6cbd0` on `#f9fafb` | **1.56:1** | 3 (large) | ✗ fail |
| Muted copy `#697078` on white | 5.01:1 | 4.5 | ✓ (borderline style) |
| White on blue button `#0876d1` | 4.64:1 | 4.5 | ✓ barely |
| Footer small text on `#090a0b` | 4.87–6:1 | 4.5 | ✓ |

**Fix:** darken the greys one step — dates/metas → `#6b727a` (≈4.6:1), eyebrows → `#5f6a72`, step numbers → `#9aa3aa` minimum (or treat as decorative `aria-hidden` and keep visual weight). Blue button text passes; for a comfortable margin darken to `--blue-dark:#0056a2` as the default button fill (7.36:1).

Also: `--muted` is used for *both* body paragraphs and tiny meta — give meta its own token so you can tune them independently.

---

## 4. Layout, spacing & visual rhythm

**4.1 No spacing scale.** Gaps are ad-hoc magic numbers: 38, 45, 55, 75, 105, 110, 130px… Introduce tokens (`--space-1:8px … --space-16:128px`) and re-map. The `110px` column gap in split sections vs `14px` in service cards is a bigger contrast than the visual language wants.

**4.2 Container width.** `--max:1100px` with 80px gutters is narrow on modern desktops (the reference runs ~1200px). Raise to `--max:1200px` and consider 24px gutters on mobile instead of 18px.

**4.3 Mobile hero controls overlap (real bug).** At ≤700px both `.hero-controls` and `.scroll-cue` land in the bottom-right corner (`right:18px`, `bottom:20px` vs `22px`) — they physically overlap (`style.css:24-25` + `:76`). Fix: keep controls bottom-right; pin the scroll cue bottom-**left** (its desktop position) or hide it on mobile — a scroll cue on touch devices is redundant anyway.

**4.4 Negative-margin hacks.** `.search-summary{margin:-25px 0 45px}` and `.post-hero .site-header{margin-bottom:90px}` are brittle; replace with layout (e.g. put the summary inside the hero flow, use padding on `.post-hero`).

**4.5 Ragged grids.** 3 testimonials in a 2-column grid leave a hole (2+1). Either 3 columns on desktop, 2×2 with a 4th quote, or a stacked list like the reference. Same for `steps-row` at ≤760px (2×2 with uneven copy lengths).

**4.6 Section rhythm is good.** 120/85/68px vertical padding and the dark → light → soft alternation are well tuned. Keep.

---

## 5. Component review

**Buttons** — Pill shape matches the reference. Issues: 12px label (§2), `gap:18px` before the arrow, `transition:.25s ease` (all-properties — should be `transition:transform .25s,box-shadow .25s`), hover lift `translateY(-3px)` is nice but there are **no `:active` states** — add `transform:translateY(-1px) scale(.98)` for press feedback. The `↗` glyph should become an SVG (§6).

**Property cards** — Solid structure (tag, status/location eyebrow row, price, meta rule). Fixes: price "Sur demande" needs a secondary affordance ("Nous consulter → WhatsApp"); rental `40.000 XOF` must say **`/mois`** — ambiguity on price is the biggest trust killer in real estate; `.property-card__heading strong{white-space:nowrap}` can collide with long titles at 2-col widths.

**Featured property "play-dot"** — A white circle with `↗` reads as a *video play button* (it's named `.play-dot`) but links to the property page. Either put a real play/gallery affordance or replace with a clear "Voir le bien" chip. Currently it's a misleading signifier.

**Service cards** — Good. But the corner `↗` link (`position:absolute;right:22px;bottom:22px`) has no text — screen readers get "link, arrow". The `aria-label` on home compensates; the services page link ("En savoir plus") should match everywhere. Minimum: give the home card links visible hover states on the whole card (`card:hover{…}` + stretched link pattern).

**FAQ accordion** — The inverted open state (black panel) is distinctive and works. Fixes: summary 13px → 15px; the `+`/`×` icon built from pseudo-elements is fine but should match the SVG icon set; only one panel open at a time is *not* enforced — decide (accordion vs multi-open) and style accordingly.

**Contact cards** — Icon `◔` for WhatsApp is wrong (§6); 32px icon circles vs the 44px rule in dead CSS. The reference puts icons **on the right** of each card with big value text — current left-icon layout is fine, but increase value text to 15–16px.

**Footer** — Structure matches the reference well (newsletter + 3 link columns + bottom bar). Issues: newsletter is a **disabled dead control** (see §10.2); only one social (WhatsApp) with a 24px hit area (raise to 36–40px visible); no legal links (§13); footer logo inherits the 8px offset (§1.1).

**Hero search widget** — Concept is good (reference has none; this is a conversion asset). 9px labels, 11px selects are far too small; give it `min-height:48px` controls and 13px labels. The `⌕` glyph in `.property-search__mark` should be an SVG magnifier.

**Map placeholder** — The fake gradient map with `⌖` pin is charming in a prototype but reads as "unfinished" in production. Swap for an OpenStreetMap/Leaflet embed (no API key) or a static map image of the Ouagadougou address.

---

## 6. Iconography (cross-cutting)

Full inventory of glyph icons currently used: `⌂ ↗ ◈ ⌕ ☎ ◔ ✉ ⌖ ⌁ ♡ ✓ ← → ↓ ↑ Ⅱ ▶` plus SVG chevrons and the footer WhatsApp SVG.

**Problems:** (1) Unicode symbols map to different fonts per platform — Windows Segoe UI Symbol vs Apple Symbols vs Android Noto give visibly different weights/shapes; (2) semantic mismatches (`◔` = WhatsApp?, `↗` = play?, `⌁` = "finitions"?); (3) optical sizing chaos: 16px, 18px, 19px, 20px, 22px, 23px, 25px glyphs in 32/34/37/40/44px circles.

**Fix:** Adopt one family (inline SVG sprite — Lucide/Feather-style 24px grid, 1.75 stroke). Map: house (⌂→home), key/tag (location), shield (gestion), magnifier/lifebuoy (conseil), phone/mail/WhatsApp (brand mark), map-pin, bed, bath, ruler, arrows (arrow-up-right for CTAs). Standardize containers: 40px circle for contact, 36px square-rounded for services, 20px inline glyphs in meta rows. Delete every Unicode icon except typographic arrows in text links (or replace those too — consistency wins).

---

## 7. Imagery & asset strategy

**7.1 Low-res sources upscaled everywhere.** `includes/data.php` uses **525×328 WordPress thumbnails** as full-bleed heroes (`min-height:760px`), 380px-tall featured cards, and 440px gallery images — a ~3× upscale; it will look soft on any retina/large display. **Fix:** request original uploads (or WP `large`/`-scaled` variants, ≥1920px for heroes, ≥900px for cards), serve with `srcset/sizes`, keep 525px only as the mobile candidate. Hero slides should be AVIF/WebP.

**7.2 Third-party hotlinking.** All property/news images load from `gelpaz.com`, team from `images.unsplash.com` — three origins of latency and breakage risk (if gelpaz.com is down or renames uploads, this site shows broken images). **Fix:** download into `assets/images/` (respecting the stated content-fidelity goal), optimize (WebP + fallback), and self-host. Add `width/height` attributes on *all* images (only hero slides have them today) to eliminate CLS.

**7.3 Inauthentic content assets.**
- Team section: Unsplash stock with labels like "L'équipe Gelpaz / Nos conseillers" — visitors to an agency site want *named humans with faces* (the reference kit shows named agents). Real photos and titles will lift trust more than any layout tweak.
- "Ils nous font confiance" strip shows text placeholders (`GELPAZ IMMO BURKINA PARTENAIRES PROJETS`) — either real partner logos (grayscale hover is already styled) or remove the strip; fake wordmarks damage credibility.
- Partner logos (`Capture.png`, `WhatsApp-Image-…jpeg`) are ad-hoc files with inconsistent aspect; the grayscale filter helps but normalize them into equal boxes.

**7.4 Logo asset** — see §1.1: crop, export white + color variants (SVG preferred).

**7.5 `screenshots.zip` (2.3MB) sits in the web root** — publicly downloadable design-kit material, also bloating deployments. Move it to repo docs or git-lfs outside the served directory (and add a `RewriteRule` deny if it must stay).

---

## 8. Responsive & mobile UX

**Good:** real breakpoints (900/760/430), mobile menu with accordion submenus, sensible single-column collapses, 430px refinements.
**Issues:**
1. **Hero controls vs scroll-cue overlap** (§4.3) — visible bug.
2. Mobile nav panel `top:68px` under a 72px header — 4px overlap; use `top:calc(100% + 8px)` from the header, not absolute magic.
3. Tablet (761–900px) squeezes nav labels to **10px** — worst-case of the type problem; switch to the burger earlier (≤980px) and keep labels ≥13px.
4. Tap targets: footer socials 24px (make 36–40px), hero dots 25px (ok, ≥24), `.text-link` small. WCAG 2.5.8 wants ≥24px; comfort wants ~44px for primary actions.
5. `body.is-menu-open{overflow:hidden}` is right, but the menu is a floating card — consider a full-screen sheet with larger hit areas for a market where most traffic is mobile (BF mobile-first).
6. Home hero stacks eyebrow + huge H1 + copy + 2 buttons + full search widget + proof row in 660px — content now exceeds it on small phones, pushing the proof row under the fold with `overflow:hidden` clipping risk on the slideshow layer. On ≤430px consider collapsing the search widget to a single "Rechercher un logement" button that opens the properties page filters.

---

## 9. Accessibility (WCAG 2.2 AA target)

**Already good:** semantic landmarks (header/nav/main/footer), `lang="fr"`, skip-worthy heading order, `:focus-visible` outline (3px), `sr-only` labels, `aria-expanded/controls` on menus, Escape-to-close, FAQ `<details>`, reduced-motion for the slideshow + scroll behavior.

**Gaps:**
1. **Skip link missing** — add "Aller au contenu" as first focusable element.
2. **Contrast failures** (§3) — 6 pairs.
3. `aria-live="polite"` on the hero slideshow announces every auto-rotation to screen readers — remove or set `aria-live="off"` and announce only on manual navigation.
4. Form labels in the hero search wrap `<select>`s inside `<label>` (ok) but 9px labels fail readability (§2); the contact checkbox needs a linked privacy policy (§13).
5. Reduced motion: hover `translateY` lifts and image zooms still animate — wrap in `@media (prefers-reduced-motion: reduce){ *{transition:none!important;animation:none!important} }`.
6. Footer socials: 24px target + `aria-label` present (good) — enlarge.
7. `time` elements use human dates ("09 septembre 2026") without `datetime="2026-09-09"` attributes.
8. Decorative glyphs (`play-dot`, step numbers) should be `aria-hidden="true"`.

---

## 10. UX flows & conversion (lead generation)

**10.1 CRITICAL — the contact form destroys inquiries.** `includes/bootstrap.php` sets `$contact_success = false` with a comment that no mail backend exists — yet the form renders fully *enabled* with `required` fields and a submit button. A visitor who writes a message and clicks "Envoyer le message" gets a page reload, cleared fields, and **no confirmation and no delivery**. The small "en cours de mise en place" note above does not excuse a data-loss path.
**Fix (in order of speed):** (a) today — `action="mailto:infos@gelpaz.com"` fallback or hide the form and show a big phone/WhatsApp CTA block; (b) this week — wire Formspree/Brevo/PHP `mail()` + success state (`.form-success` CSS already exists!); (c) never ship a form that can silently drop data.

**10.2 Newsletter is a disabled decoration.** Same class of problem: disabled input + button + apology note. Either implement or replace the block with WhatsApp/phone CTA ("Recevez nos nouveautés sur WhatsApp") — more relevant for the market anyway.

**10.3 No persistent conversion path.** The header CTA scrolls away (§1.5). For this audience (phone/WhatsApp-driven), add: (a) sticky header with CTA on scroll; (b) a floating WhatsApp button (bottom-left, distinct from back-to-top bottom-right) — standard practice in West African real-estate sites and high-converting; (c) `tel:` links as buttons on the property detail sticky summary.

**10.4 Price & availability friction.** "Sur demande" on 5 of 6 properties + rental price without period. Even "À partir de X XOF" or "Budget: nous consulter" with an instant WhatsApp deep-link (`wa.me/22667308185?text=Bonjour, je suis intéressé par le modèle F4C`) reduces friction dramatically. Prefill property name — it's a one-line template change in `render_property_card`.

**10.5 All blog cards link to the same article.** `render_blog_card()` hardcodes `page_url('post')` for every post, and `pages/post.php` shows one fixed article. At minimum add `id` to `$posts` and render the matching title/content; dead-ends here hurt trust ("which article am I reading?").

**10.6 Search widget dead-ends.** Hero search works (filters properties) — good — but "Ordre par défaut / Surface croissante" is the only sort; price sort is impossible with "Sur demande". Fine for now; the `search-summary` reset link is good UX.

---

## 11. Performance & technical polish

1. **Self-host images** (§7.2) — biggest LCP win. The hero LCP image is a remote 525px JPEG upscaled to 1440px+.
2. **Fonts:** Google Fonts loads DM Sans (400/500/600/700) + Playfair (500/600/ital500) = 6 faces. Drop unused weights (600?), add `font-display:swap` (already via URL), consider self-hosting WOFF2 for one less origin.
3. **`.htaccess` upgrades:** cache-control (`assets/` 1y immutable), gzip/deflate, `X-Content-Type-Options`, `Referrer-Policy`, HTTPS redirect — none present today.
4. **CSS hygiene:** `style.css` puts component rules *before* the reset/base (line 62 is `*{box-sizing…}`) with duplicate and conflicting rules (§2.3). Reorder: tokens → reset → base → layout → components → utilities → responsive; or split files. Minify for production.
5. `transition:.25s ease` → explicit properties (paint cost).
6. Remove `screenshots.zip` from the deployable root (§7.5).
7. `app.js` is clean; the slideshow's 7s autoplay + pause-on-hover is well built (respect `visibilitychange` — already done). Nice work there.

---

## 12. SEO & discoverability

1. **No favicon** — add SVG + PNG + `apple-touch-icon` (the logo is ready to crop, §1.1). Missing favicon = instant "unfinished" signal in tabs/bookmarks.
2. **Titles:** home title is `La différence · GELPAZ IMMO` — poetic but poor for search. Use "GELPAZ IMMO — Vente & location de logements à Ouagadougou". Keep pattern `Page · GELPAZ IMMO` for inner pages.
3. **No Open Graph/Twitter tags** — shares on WhatsApp/Facebook (your primary channels!) show empty previews. Add `og:title/description/image` (use a property photo).
4. **No canonical URLs, no `robots.txt`, no `sitemap.xml`.**
5. **Pretty URLs unused:** `.htaccess` + `bootstrap.php` map real gelpaz.com paths (`estate_property/…`, `nos-offres-immobilieres`…), but `page_url()` emits `index.php?page=…` for **every internal link**. Make `page_url()` emit the pretty paths the router already understands — cleaner sharing + SEO.
6. **Silent home fallback:** unknown routes render the home page with 200 status. Ship a real 404 (the reference kit has an error-404 design) — silent fallback is bad UX and worse SEO.
7. Add `LocalBusiness`/`RealEstateAgent` JSON-LD (name, address DAGNOEN rue 29.128, phone, hours, geo) — free rich results.

---

## 13. Content & trust signals

1. **Legal links missing entirely** — the consent checkbox promises data use but there's no privacy policy; add "Mentions légales · Politique de confidentialité · CGU" to the footer bottom bar (also required for ad networks later).
2. **Opening hours exist in data (`$site['hours']`) but are never displayed** on the contact page (only inside one FAQ answer). Show hours + address + map pin on the contact aside.
3. Testimonials are strong and specific (real names, "cité de l'intégration") — keep and consider adding a photo or WhatsApp-style verification cue.
4. "100% d'écoute & d'engagement" hero proof is vague filler; swap one stat for something concrete (e.g. "350+ familles accompagnées" if true).
5. Property "Modèle F4C" naming is industrial; pair with a lifestyle title ("Villa familiale — Centre") + keep the model code as meta.
6. FAQ covers the right questions. Add one on financing/installment plans ("souscription") since pricing page promises it.

---

## 14. Prioritized roadmap

### P0 — this week (high impact, low effort)
| # | Task | Where | Effort |
|---|---|---|---|
| 1 | Center logo, remove `margin-top:8px`, crop PNG, white variant | `style.css:64`, `assets/images` | 1h |
| 2 | Fix mobile hero controls/scroll-cue overlap | `style.css:24-25` | 15m |
| 3 | Fix 6 contrast failures | `style.css` tokens | 30m |
| 4 | Contact form: wire backend or replace with phone/WhatsApp CTA | `pages/contact.php`, `bootstrap.php` | 1–3h |
| 5 | Type floor 12–13px (kill 9/10/11px text) | `style.css` | 1–2h |
| 6 | `/mois` on rental price + WhatsApp deep-link on cards | `data.php`, `functions.php` | 30m |
| 7 | Favicon + OG tags + better titles | `index.php` | 45m |
| 8 | Move `screenshots.zip` out of web root | repo root | 5m |

### P1 — this month (craft)
| # | Task | Effort |
|---|---|---|
| 9 | SVG icon system; replace all Unicode glyphs; fix WhatsApp icon | 3h |
| 10 | Header rebuild: text-anchored underlines, `top:100%` dropdowns, gap tokens, active states on dropdown parents | 3h |
| 11 | Sticky header + floating WhatsApp button | 2h |
| 12 | Self-host + resize image pipeline (≥1920px heroes, srcset) | 4h |
| 13 | Real 404 page + pretty URLs from `page_url()` + robots/sitemap | 3h |
| 14 | Post page header nesting bug; per-post routing | 2h |
| 15 | Newsletter: implement or replace with WhatsApp CTA | 1h |
| 16 | Legal links + privacy page | 2h |

### P2 — next quarter (elevation)
| # | Task | Effort |
|---|---|---|
| 17 | Real team photos/names, real partner logos, real testimonials media | ongoing |
| 18 | Interactive map (Leaflet/OSM) for properties + contact | 3h |
| 19 | Property gallery lightbox + real multi-angle photos | 4h |
| 20 | Spacing-scale refactor + CSS reorganization/minification | 4h |
| 21 | WhatsApp-flows automation (prefilled inquiry per property), lead tracking | 4h |
| 22 | `RealEstateAgent` JSON-LD, hreflang/lang review, Lighthouse ≥ 95 pass | 3h |

---

### Appendix A — Strengths worth protecting
- Playfair italic accent word in headlines — signature, on-brand with the reference kit.
- Dark/light/soft section alternation and 120px vertical rhythm.
- Pill buttons + arrow micro-interaction (translate on hover).
- FAQ inverted open state — distinctive.
- Sticky property summary card on detail pages.
- Hero slideshow respects `prefers-reduced-motion`, pauses on hover/focus — ahead of most builds.
- French microcopy quality is high ("La différence, c'est *notre* engagement").
- Mobile menu with nested accordions and Escape handling.

### Appendix B — Quick CSS patch sketch (P0 header + type)
```css
:root{--text-xs:12px;--text-sm:13px;--text-base:16px}
.brand{margin-top:0}
.brand img{height:40px;width:auto}
.main-nav>a,.nav-dropdown>button{font-size:14px;padding:8px 0}
.main-nav>a:after,.nav-dropdown>button:after{bottom:0}
.nav-dropdown__menu{top:calc(100% + 14px)}
.header-cta{margin-left:0;font-size:13px;gap:8px}
.eyebrow{color:#5f6a72;font-size:var(--text-xs)}
.post-card time,.team-card p{color:#6b727a}
.steps-row span{color:#9aa3aa}
@media(max-width:700px){.scroll-cue{left:18px;right:auto}}
@media (prefers-reduced-motion: reduce){*{transition:none!important;animation:none!important}}
```

*End of audit — 42 findings, 22 prioritized actions. Item numbers in the roadmap reference the sections above for full detail.*

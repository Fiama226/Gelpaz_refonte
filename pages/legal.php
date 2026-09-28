<?php
/** Page view: legal (mentions légales + politique de confidentialité) */
?>
    <?php render_interior_hero('Mentions légales &amp; confidentialité', 'Informations légales', $images['hero_alt']); ?>
    <main id="main"><section class="section"><div class="container legal-layout"><nav class="legal-nav" aria-label="Sommaire"><a href="#editeur">Éditeur du site</a><a href="#hebergement">Hébergement</a><a href="#propriete">Propriété intellectuelle</a><a href="#confidentialite">Données personnelles</a><a href="#cookies">Cookies</a><a href="#droits">Vos droits</a></nav><div class="legal-body">
        <h2 id="editeur">Éditeur du site</h2>
        <p>Le site gelpaz.com est édité par <b>GELPAZ IMMO</b>, société de promotion et de gestion immobilière établie au Burkina Faso.</p>
        <ul><li><b>Adresse :</b> <?= e($site['address']) ?></li><li><b>Téléphone :</b> <?= e($site['phone']) ?></li><li><b>WhatsApp :</b> <?= e($site['whatsapp']) ?></li><li><b>E-mail :</b> <a class="text-link" href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li><li><b>Horaires :</b> lundi au vendredi, <?= e($site['hours']) ?></li></ul>

        <h2 id="hebergement">Hébergement</h2>
        <p>Le site est hébergé sur une infrastructure mutualisée. Les coordonnées de l’hébergeur peuvent être obtenues sur simple demande à l’adresse <a class="text-link" href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>.</p>

        <h2 id="propriete">Propriété intellectuelle</h2>
        <p>L’ensemble des contenus présents sur ce site (textes, photographies de projets, plans, marques et éléments graphiques) est protégé. Toute reproduction ou représentation, totale ou partielle, sans autorisation écrite préalable de GELPAZ IMMO est interdite.</p>
        <p>Les visuels de projets publiés le sont à titre illustratif : les finitions, teintes et aménagements définitifs peuvent varier selon les prescriptions techniques et les validations en cours.</p>

        <h2 id="confidentialite">Données personnelles</h2>
        <p>GELPAZ IMMO traite les données transmises via le formulaire de contact ou par WhatsApp dans le seul but de répondre à votre demande et d’assurer le suivi commercial de votre projet immobilier.</p>
        <h3>Données collectées</h3>
        <ul><li>Identité : prénom, nom</li><li>Coordonnées : adresse e-mail, numéro de téléphone</li><li>Contenu du message et informations relatives à votre projet</li></ul>
        <h3>Base légale et durée de conservation</h3>
        <p>Le traitement repose sur votre consentement, recueilli lors de l’envoi du formulaire. Les données sont conservées le temps nécessaire au traitement de la demande, puis archivées conformément aux obligations légales applicables au secteur de la promotion immobilière.</p>
        <h3>Destinataires</h3>
        <p>Vos données sont destinées aux équipes commerciales de GELPAZ IMMO. Elles ne sont ni vendues, ni cédées, ni louées à des tiers à des fins publicitaires.</p>

        <h2 id="cookies">Cookies et mesure d’audience</h2>
        <p>Le site n’utilise pas de cookie publicitaire. Seuls les éléments strictement nécessaires au fonctionnement des pages (polices de caractères, ressources techniques) peuvent être chargés depuis des services tiers. L’intégration d’un service de mesure d’audience ou d’une carte interactive pourra conduire à déposer des cookies : votre consentement serait alors demandé au préalable.</p>

        <h2 id="droits">Vos droits</h2>
        <p>Vous pouvez à tout moment demander l’accès, la rectification ou la suppression des données vous concernant, ainsi que la limitation de leur traitement. Pour exercer ces droits, écrivez à <a class="text-link" href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a> ou contactez-nous par téléphone au <?= e($site['phone']) ?>.</p>
        <p>Si vous estimez que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à l’autorité compétente en matière de protection des données personnelles au Burkina Faso.</p>

        <p class="legal-updated">Dernière mise à jour : septembre 2026.</p>
    </div></div></section></main>

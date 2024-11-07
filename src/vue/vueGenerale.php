<?php

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\HTTP\Cookie;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $titre ?? 'Page' ?> </title>
    <link rel="icon" href="../ressources/images/logoRed.png">

    <link rel="stylesheet" href="../ressources/css/homestyle.css">
    <link rel="stylesheet" href="../ressources/css/charte-graphique-UM.css">
    <link rel="stylesheet" href="../ressources/css/button.css">
    <link rel="stylesheet" href="../ressources/css/connect.css">
    <link rel="stylesheet" href="../ressources/css/accessibility.css">
    <link rel="stylesheet" href="../ressources/css/messageFlash.css">
</head>
<body>
<header>
    <a href="controleurFrontal.php?controleur=Accueil&action=afficher">
        <img id="logoToggle" class="logo" src="../ressources/images/logoRed.png" alt="Logo">
    </a>
    <input type="checkbox" id="burgerToggle" hidden>

    <label for="burgerToggle" id="burgerIcon">☰</label>

    <div id="burger">
        <?php
        if (!ConnexionUtilisateur::estConnecte()) : ?>
            <a href="controleurFrontal.php?controleur=Accueil&action=afficher"
               class="item">Accueil</a>
            <a href="controleurFrontal.php?controleur=Connexion&action=afficherPreference"
               class="item">Connexion</a>
        <?php else: ?>
            <?php if (ConnexionUtilisateur::estAdministrateur()): ?>
                <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur" class="item">Utilisateurs</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=etudiant" class="item">Étudiants</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=ecole" class="item">Écoles</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=professeur" class="item">Professeurs</a>
            <?php endif; ?>
            <a href="controleurFrontal.php?controleur=utilisateur&action=deconnecter" class="item">Déconnexion</a>
        <?php endif; ?>
    </div>

    <!-- accessibility -->
    <input type="checkbox" id="accessibilityToggle" hidden>
    <label for="accessibilityToggle" id="accessibilityButton">
        <img src="../ressources/images/accessibility-icon.webp" id="accessibilityIcon" alt="Accessibility Icon">
    </label>

    <div id="accessibilityMenu">
        <input type="checkbox" id="highContrast" hidden>
        <label for="highContrast" class="accessibility-option">Contraste élevé</label>

        <input type="checkbox" id="largeFont" hidden>
        <label for="largeFont" class="accessibility-option">Augmenter la taille de la police</label>

        <input type="checkbox" id="darkMode" hidden>
        <label for="darkMode" class="accessibility-option">Mode sombre</label>
    </div>


    <nav class="navbar">
        <?php

        if (!ConnexionUtilisateur::estConnecte()) : ?>
            <a href="controleurFrontal.php?controleur=Accueil&action=afficher"
               class="nav-item">Accueil</a>
            <a href="controleurFrontal.php?controleur=Connexion&action=afficherPreference"
               class="nav-item">Connexion</a>
        <?php else: ?>
            <?php if (ConnexionUtilisateur::estAdministrateur()): ?>
                <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur" class="nav-item">Utilisateurs</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=etudiant" class="nav-item">Étudiants</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=ecole" class="nav-item">Écoles</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=professeur" class="nav-item">Professeurs</a>
            <?php endif; ?>
            <?php if (ConnexionUtilisateur::estProfesseur()): ?>
                <a href="controleurFrontal.php?action=afficherListe&controleur=etudiant" class="nav-item">Étudiants</a>
            <?php endif; ?>
            <a href="controleurFrontal.php?controleur=utilisateur&action=deconnecter" class="nav-item">Déconnexion</a>
        <?php endif; ?>
    </nav>

</header>

<main>
    <?php
    foreach (MessageFlash::lireTousMessages() as $type => $lireMessage) {
        echo '<div class="alert alert-' . $type . '">' . $lireMessage . '</div>';
    }
    /** @var string $cheminCorpsVue */
    require __DIR__ . "/{$cheminCorpsVue}";
    ?>

    <?php if (!Cookie::contient("bannerClosed")): ?>
        <div id="cookie-banner"><h2>Politique de confidentialité</h2>
            <p> Nous utilisons des cookies pour améliorer votre expérience sur notre site. Les cookies sont de petits fichiers de données enregistrés sur votre ordinateur ou appareil mobile lors de votre visite. Ils nous permettent de recueillir des informations sur votre comportement de navigation, telles que les pages que vous consultez et les services que vous utilisez.
                Ces informations nous aident à personnaliser votre expérience, à mieux comprendre l'utilisation de notre site et à améliorer nos services. En poursuivant votre navigation sur notre site, vous consentez à notre utilisation des cookies. Pour en savoir plus sur notre politique d'utilisation des cookies et sur les moyens de les contrôler, veuillez consulter notre politique de confidentialité..</p>
            <a href="controleurFrontal.php?action=setCookieBanner" class="close-button">✖</a>
        </div>
    <?php endif; ?>

</main>

<footer>
    <div class="VBox" id="footer-main-vbox">
        <div class="HBox">
            <div class="VBox" id="footer-team">
                <p>UNIVERSITÉ DE MONTPELLIER</p>
                <p>163 rue Auguste Broussonnet</p>
                <p>34090 Montpellier</p>
            </div>
            <div class="HBox" id="footer-logo">
                <img id="logoIUT"  src="../ressources/images/Logo_IUT.png" alt="Logo">
                <img id="logoUM"  src="../ressources/images/logo_um.png" alt="Logo">
            </div>
        </div>
        <div class="VBox">
            <a href="../ressources/CGU.pdf" target="_blank" class="link">Conditions Générales d'Utilisation</a>
            <p>Copyright 2024 - Tous droits réservés</p>
        </div>
    </div>
</footer>
<script src="../ressources/javascript/accessibility.js"></script>


</body>
</html>

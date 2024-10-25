<?php

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="../ressources/css/homestyle.css">
    <link rel="stylesheet" href="../ressources/css/charte-graphique-UM.css">
    <link rel="stylesheet" href="../ressources/css/button.css">
</head>
<body>
<header>
    <a href="/sae3a-base/web/controleurFrontal.php?controleur=Accueil&action=afficher">
        <img id="logoToggle" class="logo" src="../ressources/images/logoRed.png" alt="Logo">
    </a>

    <div id="burgerParent">
        <div class="burger">
            <span></span>
        </div>
    </div>

    <nav class="navbar">
        <?php

        if (!ConnexionUtilisateur::estConnecte()) : ?>
            <a href="/sae3a-base/web/controleurFrontal.php?controleur=Accueil&action=afficher"
               class="nav-item">Accueil</a>
            <a href="/sae3a-base/web/controleurFrontal.php?controleur=Connexion&action=afficherPreference"
               class="nav-item">Connexion</a>
        <?php else: ?>
            <?php if (ConnexionUtilisateur::estAdministrateur()): ?>
                <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur" class="nav-item">Utilisateurs</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=etudiant" class="nav-item">Étudiants</a>
                <a href="controleurFrontal.php?action=afficherListe&controleur=ecole" class="nav-item">Écoles</a>
            <?php endif; ?>
            <a href="/sae3a-base/web/controleurFrontal.php?controleur=utilisateur&action=deconnecter" class="nav-item">Déconnexion</a>
            <!--            <nav>-->
            <!--                <form action="controleurFrontal.php" method="get">-->
            <!--                    <input type="hidden" name="controleur" value="Connexion">-->
            <!--                    <input type="hidden" name="action" value="deconnecter">-->
            <!--                    <button type="submit">Déconnexion</button>-->
            <!--                </form>-->
            <!--            </nav>-->
        <?php endif; ?>
    </nav>
</header>

<main>
    <?php
    /** @var string $cheminCorpsVue */
    require __DIR__ . "/{$cheminCorpsVue}";
    ?>
</main>

<footer>
    <div class="VBox">
        <div class="HBox">
            <div class="VBox">
                <p>Contactez-nous !</p>
            </div>
            <div class="HBox" id="footer-team">
                <img id="logoIUT"  src="../ressources/images/Logo_IUT.png" alt="Logo">
                <img id="logoUM"  src="../ressources/images/logo_um.png" alt="Logo">
            </div>
            <div class="VBox">
                <p>Site de SAE 2024/2025 Semestre 3</p>
            </div>
        </div>
        <br>
        <a href="../ressources/CGU.pdf" target="_blank" class="link">Conditions Générales d'Utilisation</a>
        <p>Copyright 2024 - Tous droits réservés</p>
    </div>
</footer>
</body>
</html>

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
    <img id="logoToggle" class="logo" src="/ressources/images/logo" alt="Logo"> <!-- need to design a logo -->

    <div id="burgerParent">
        <div class="burger">
            <span></span>
        </div>
    </div>

    <nav class="navbar">
        <a href="/sae3a-base/web/controleurFrontal.php?controleur=Accueil&action=afficher" class="nav-item">Accueil</a>
    </nav>

    <nav>
        <form action="controleurFrontal.php" method="get">
            <input type="hidden" name="controleur" value="Connexion">
            <input type="hidden" name="action" value="deconnecter">
            <button type="submit">Déconnexion</button>
        </form>
    </nav>
</header>

<main>
    <?php
    /** @var string $cheminCorpsVue */
    require __DIR__ . "/{$cheminCorpsVue}";

    if (isset($_SESSION['type']) && $_SESSION['type'] === 'administrateur') {
        echo '<ul>';
        echo '<li><a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Gestion des utilisateurs</a></li>';
        echo '<li><a href="controleurFrontal.php?action=afficherListe&controleur=etudiant">Gestion des étudiants</a></li>';
        echo '<li><a href="controleurFrontal.php?action=afficherListe&controleur=ecole">Gestion des écoles</a></li>';
        echo '</ul>';
    }
    ?>
</main>

<footer>
    <div class="VBox">
        <div class="HBox">
            <div class="VBox">
                <p>Contactez-nous !</p>
            </div>
            <div class="VBox" id="footer-team">
                <p>Notre équipe</p>
                <a class="link" href="https://www.linkedin.com/in/daniele-dainiute/">Daniele DAINIUTE</a>
                <a class="link" href="https://www.linkedin.com/in/paul-godek-2597712aa/">Paul GODEK</a>
                <a class="link" href="https://www.linkedin.com/in/nghonghoa/">Hong Hoa NGUYEN</a>
                <a class="link" href="https://www.linkedin.com/in/kilyan-somb%C3%A9-b651842ab/">Kilyan SOMBÉ</a>
            </div>
            <div class="VBox">
                <p>Site de SAE 2024/2025 Semestre 3</p>
            </div>
        </div>
        <br>
        <a href="/sae3a-base/web/CGU.pdf" target="_blank" class="link">Conditions Générales d'Utilisation</a>
        <p>Copyright 2024 - Tous droits réservés</p>
    </div>
</footer>
</body>
</html>

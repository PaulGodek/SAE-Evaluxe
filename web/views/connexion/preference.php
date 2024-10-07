<?php include __DIR__ . '/../header.php'; ?>
<main>

    <div class="container">
        <h2 id="remplaceBaliseLegend">Choisissez votre compte</h2>
        <div class="HBox">
            <a id="adminChoice" class="button" href="/sae3a-base/web/controleurFrontal.php?action=afficherAdministrateur&controleur=Connexion">Compte Administrateur</a>
            <a id="etudiantChoice" class="button" href="/sae3a-base/web/controleurFrontal.php?action=afficherEtudiant&controleur=Connexion">Compte Etudiant</a>
            <a id="ecoleChoice" class="button" href="/sae3a-base/web/controleurFrontal.php?action=afficherEcole&controleur=Connexion">Compte Ecole</a>
        </div>
        <br>
        <p>En vous connectant ou en vous inscrivant, vous confirmez avoir lu et accepté les <a href="CGU.pdf" target="_blank" class="link">Conditions Générales d'Utilisation</a> de ce site web.</p>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>
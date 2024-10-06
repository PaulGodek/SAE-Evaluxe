<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherDetail"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom">Nom de l'étudiant</label>
            <input class="InputAddOn-field" type="text" name ="nom" placeholder="Ex : Dupont "  id="nom" required>
        </p>

        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Rechercher" />
        </p>
    </fieldset>
</form>
<?php

/** @var Etudiant[] $etudiants */
use App\GenerateurAvis\Modele\DataObject\Etudiant;

echo "<h2>Liste des étudiants</h2> 
        
    <p><a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParNom'>  Trier par nom  </a>&emsp;<a href='controleurFrontal.php?controleur=etudiant&action=afficherListeEtudiantOrdonneParMoyenne'>  Trier par moyenne  </a></p> 
    
<ul>";
    foreach ($etudiants as $etudiant) {
    $loginHTML = htmlspecialchars($etudiant->getLogin());
    $loginURL = rawurlencode($etudiant->getLogin());
    echo '<li><p> L\'étudiant <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $loginHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJourEtudiant&nom=' . $loginURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimerEtudiant&nom=' . $loginURL . '">Supprimer ?</a>)</p></li>';
    }



<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherDetailEtudiant"/>
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
        
    <p><a href='controleurFrontal.php?action=afficherListeEtudiantOrdonneParNom'>  Trier par nom  </a>&emsp;<a href='controleurFrontal.php?action=afficherListeEtudiantOrdonneParMoyenne'>  Trier par moyenne  </a></p> 
    
<ul>";
    foreach ($etudiants as $etudiant) {
    $nomHTML = htmlspecialchars($etudiant->getNom());
    $nomURL = rawurlencode($etudiant->getNom());
    echo '<li><p> L\'étudiant <a href="controleurFrontal.php?action=afficherDetailEtudiant&nom=' . $nomURL . '">' . $nomHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJourEtudiant&nom=' . $nomURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimerEtudiant&nom=' . $nomURL . '">Supprimer ?</a>)</p></li>';
    }



<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherDetailEcole"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom">Nom de l'école</label>
            <input class="InputAddOn-field" type="text" name ="nom" placeholder="Ex : IUT Montpellier"  id="nom" required>
        </p>

        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Rechercher" />
        </p>
    </fieldset>
</form>
<?php

/** @var Ecole[] $ecoles */
use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<h2>Liste des écoles</h2> 
        
    <p><a href='controleurFrontal.php?action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp;<a href='controleurFrontal.php?action=afficherListeEcoleOrdonneParAdresse'>  Trier par adresse  </a></p> 
    
<ul>";
    foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $nomURL = rawurlencode($ecole->getNom());
    echo '<li><p> L\'école <a href="controleurFrontal.php?action=afficherDetailEcole&nom=' . $nomURL . '">' . $nomHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJourEcole&nom=' . $nomURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimerEcole&nom=' . $nomURL . '">Supprimer ?</a>)</p></li>';
    }



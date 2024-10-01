<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherListeEcole"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomEcole">Nom de l'école</label>
            <input class="InputAddOn-field" type="text" placeholder="Ex : IUT Montpellier"  id="nomEcole" required>
        </p>

        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Rechercher" />
        </p>
    </fieldset>
</form>
<?php

/** @var Ecole[] $ecoles */
use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<h2>Liste des écoles</h2><ul>";
    foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $nomURL = rawurlencode($ecole->getNom());
    echo '<li><p> L\'école <a href="controleurFrontal.php?action=afficherDetailEcole&nom=' . $nomURL . '">' . $nomHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJourEcole&nom=' . $nomURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimerEcole&nom=' . $nomURL . '">Supprimer ?</a>)</p></li>';
    }
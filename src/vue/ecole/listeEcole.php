<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherListeEcole"/>
    <fieldset>
        <legend>Mon formulaire :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomEcole">Login&#42;</label>
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
    echo '<li><p> l\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $nomURL . '">' . $nomHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJour&login=' . $nomURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimer&login=' . $nomURL . '">Supprimer ?</a>)</p></li>';
    }
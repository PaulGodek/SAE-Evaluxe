<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheEcole"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="reponse">Nom/prenom du professeur</label>
            <input class="InputAddOn-field" type="text" name ="reponse" placeholder="Ex : Dupont "  id="reponse" required>
        </p>

        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Rechercher" />-->
            <button class = "button-submit" type="submit">Rechercher</button>
        </p>
    </fieldset>
</form>


<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "<h2>Liste des écoles</h2> 
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListe'>  Trier par validation  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParVille'>  Trier par ville  </a></p> 
<ul>";

/** @var Ecole[] $ecoles */
foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $loginURL = rawurlencode($ecole->getLogin());

    if (!$ecole->isEstValide()) {
        echo '<li><p>L\'école <a href="controleurFrontal.php?controleur=ecole&action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> 
                  &ensp; <a href="controleurFrontal.php?controleur=ecole&action=valider&login=' . $loginURL . '">Valider</a> </p></li>';
    } else {
        echo '<li><p>L\'école <a href="controleurFrontal.php?controleur=ecole&action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a></p></li>';
    }
}

echo "</ul>";

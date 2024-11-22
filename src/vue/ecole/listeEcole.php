<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheEcole"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomID">Nom de l'école</label>
            <input class="InputAddOn-field" type="text" name="nom" placeholder="Ex : Polytech" id="nomID" required>
        </p>

        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Rechercher" />-->
            <button class="button-submit" type="submit">Rechercher</button>
        </p>
    </fieldset>
</form>


<?php

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;

echo "<h2>Liste des écoles</h2> 
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListe'>  Trier par validation  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParVille'>  Trier par ville  </a></p> 
<ul>";

/** @var Ecole[] $ecoles */
/** @var Etudiant $etudiant */

if (ConnexionUtilisateur::estEtudiant()) {
    $loginEtudiantURL = urlencode($etudiant->getEtudiant()->getLogin());
}

foreach ($ecoles as $ecole) {

    $nomHTML = htmlspecialchars($ecole->getNom());
    $loginURL = rawurlencode($ecole->getEcole()->getLogin());

    if (ConnexionUtilisateur::estAdministrateur()) {
        if (!$ecole->isEstValide()) {
            echo '<li><p>L\'école <a href="controleurFrontal.php?controleur=ecole&action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> 
                      &ensp; <a href="controleurFrontal.php?controleur=ecole&action=valider&login=' . $loginURL . '">Valider</a> </p></li>';
        } else {
            echo '<li><p>L\'école <a href="controleurFrontal.php?controleur=ecole&action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a></p></li>';
        }
    } else {
        if ($etudiant->dejaDemande($ecole->getNom()) && !in_array($etudiant->getCodeUnique(), $ecole->getFutursEtudiants())) {
            echo '<li><p>L\'école ' . $nomHTML . ' demande l\'accès à vos notes <a href="controleurFrontal.php?controleur=ecole&action=accepterDemande&login=' . $loginURL . '&loginEtudiant=' . $loginEtudiantURL . '"> Accepter</a> &nbsp; <a href="controleurFrontal.php?controleur=ecole&action=refuserDemande&login=' . $loginURL . '&loginEtudiant=' . $loginEtudiantURL . '"> Refuser</a></p></li>';
        }
    }
}

echo "</ul>";

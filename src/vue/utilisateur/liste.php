<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRecherche"/>
    <input type="hidden" name="type" value="administrateur"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login">Login de l'utilisateur</label>
            <input class="InputAddOn-field" type="text" name ="login" placeholder="Ex : nomp "  id="login" required>
        </p>

        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Rechercher" />
        </p>
    </fieldset>
</form>


<?php
/** @var Utilisateur[] $utilisateurs */

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

echo "<h2>Liste des utilisateurs</h2><ul>
<p><a href='controleurFrontal.php?action=afficherListeUtilisateurOrdonneParLogin'>Trier par login  </a>&emsp;<a href='controleurFrontal.php?action=afficherListeUtilisateurOrdonneParType'>Trier par type  </a></p> 
    
<ul>";
foreach ($utilisateurs as $utilisateur) {
    $loginHTML = htmlspecialchars($utilisateur->getLogin());
    $loginURL = rawurlencode($utilisateur->getLogin());
    echo '<li><p> Utilisateur de login <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $loginHTML . '</a> (<a href="controleurFrontal.php?action=afficherFormulaireMiseAJour&login=' . $loginURL . '">Modifier ?</a>, <a href="controleurFrontal.php?action=supprimer&login=' . $loginURL . '">Supprimer ?</a>)</p></li>';
}
echo '</ul><p><a href="controleurFrontal.php?action=afficherFormulaireCreationEtudiant">Créer un etudiant</a>&emsp;<a href="controleurFrontal.php?action=afficherFormulaireCreationEcole">Créer une école</a> </p>';
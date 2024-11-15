<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheProfesseur"/>
    <input type="hidden" name="controleur" value="professeur"/>
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
/** @var Professeur[] $professeurs */

use App\GenerateurAvis\Modele\DataObject\Professeur;

echo "<h2>Liste des professeurs</h2> 
        
    <p><a href='controleurFrontal.php?controleur=professeur&action=afficherListeProfesseurOrdonneParNom'>  Trier par nom  </a>&emsp; <a href='controleurFrontal.php?controleur=professeur&action=afficherListeProfesseurOrdonneParPrenom'>  Trier par prenom  </a></p> 
    
<ul>";
foreach ($professeurs as $professeur) {
    $nomHTML = htmlspecialchars($professeur->getNom());
    $prenomHTML = htmlspecialchars($professeur->getPrenom());
    $loginURL = rawurlencode($professeur->getLogin());
    echo '<li><p> Le professeur <a href="controleurFrontal.php?controleur=professeur&action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '&nbsp;'.$prenomHTML .'</a></p></li>';
}

echo "</ul>";

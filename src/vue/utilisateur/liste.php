<link rel="stylesheet" href="../ressources/css/connect.css">

<div class="container">
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="afficherResultatRechercheUtilisateur"/>
    <input type="hidden" name="type" value="administrateur"/>
    <input type="hidden" name="controleur" value="utilisateur"/>
    <fieldset>
        <legend>Recherche :</legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login">Login de l'utilisateur</label>
            <input class="InputAddOn-field" type="text" name ="login" placeholder="Ex : nomp "  id="login" required>
        </p>

        <p class="InputAddOn">
<!--            <input class="InputAddOn-field" type="submit" value="Rechercher" />-->
            <button class = "button-submit" type="submit">Rechercher</button>
        </p>
    </fieldset>
</form>
</div>


<?php
/** @var Utilisateur[] $utilisateurs */

use App\GenerateurAvis\Modele\DataObject\Utilisateur;
// https://dev.to/dcodeyt/creating-beautiful-html-tables-with-css-428l
// pour faire de joli tableaux de données
echo "<div class='container'> <h2>Liste des utilisateurs</h2>
 
    
<ul>";
$type='10';
foreach ($utilisateurs as $utilisateur) {

    if($utilisateur->getType() != "administrateur"){

        $loginHTML = htmlspecialchars($utilisateur->getLogin());
        $loginURL = rawurlencode($utilisateur->getLogin());
        if($type!=$utilisateur->getType()){
            if($utilisateur->getType()=="etudiant"){
                echo '<h3>Étudiants :</h3>';
            }else if($utilisateur->getType()=="universite"){
                echo '<h3>Écoles :</h3>';
            }else if($utilisateur->getType()=="professeur"){
                echo '<h3>Enseignants :</h3>';
            }

        }

        echo '<li><p> Utilisateur de login <a href="controleurFrontal.php?controleur=utilisateur&action=afficherDetail&login=' . $loginURL . '">' . $loginHTML . '</a></p></li>';

    }
    $type=$utilisateur->getType();

}
echo "</ul></div>";
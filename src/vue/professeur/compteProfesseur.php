
<link rel="stylesheet" type="text/css" href="../ressources/css/account.css">


<?php

/** @var Professeur $user */

use App\GenerateurAvis\Modele\DataObject\Professeur;

$login = $user->getUtilisateur()->getLogin();
$loginURL = urlencode($login);

echo '<div class="infosCompte">';
echo "<h2> Vos informations </h2>";

echo "<p><span class='sousTitre'>Votre login : </span>" . htmlspecialchars($login)."</p>";

echo "<p><span class='sousTitre'>Votre nom : </span>" . htmlspecialchars($user->getNom())."</p>";

echo "<p><span class='sousTitre'>Votre adresse : </span>" . htmlspecialchars($user->getPrenom())."</p>";

echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon compte</a>';



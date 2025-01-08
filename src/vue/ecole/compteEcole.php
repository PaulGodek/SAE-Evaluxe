<link rel="stylesheet" type="text/css" href="../ressources/css/account.css">




<?php

/** @var Ecole $user */

use App\GenerateurAvis\Modele\DataObject\Ecole;

$login = $user->getUtilisateur()->getLogin();
$loginURL = urlencode($login);
echo '<div class="infosCompte">';
echo "<h2> Vos informations </h2>";

echo "<p><span class='sousTitre'>Votre login : </span>" . htmlspecialchars($login)."</p>";
echo "<p><span class='sousTitre'>Votre nom : </span>" . htmlspecialchars($user->getNom())."</p>";
echo "<p><span class='sousTitre'>Votre adresse : </span>" . htmlspecialchars($user->getAdresse())."</p>";
echo "<p><span class='sousTitre'>Votre ville : </span>" . htmlspecialchars($user->getVille())."</p>";
echo "<p><span class='sousTitre'>Votre adresse mail : </span>" . htmlspecialchars($user->getAdresseMail())."</p>";
echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon compte</a>';
echo '</div>';



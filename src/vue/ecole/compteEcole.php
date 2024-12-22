<?php

/** @var Ecole $user */

use App\GenerateurAvis\Modele\DataObject\Ecole;

$login = $user->getUtilisateur()->getLogin();
$loginURL = urlencode($login);
echo "Votre login : " . htmlspecialchars($login);
echo "<br>";
echo "Votre nom : " . htmlspecialchars($user->getNom());
echo "<br>";
echo "Votre adresse : " . htmlspecialchars($user->getAdresse());
echo "<br>";
echo "Votre ville : " . htmlspecialchars($user->getVille());
echo "<br>";
echo "Votre adresse mail : " . htmlspecialchars($user->getAdresseMail());
echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon compte</a>';



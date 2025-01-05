<?php

/** @var Professeur $user */

use App\GenerateurAvis\Modele\DataObject\Professeur;

$login = $user->getUtilisateur()->getLogin();
$loginURL = urlencode($login);
echo "Votre login : " . htmlspecialchars($login);
echo "<br>";
echo "Votre nom : " . htmlspecialchars($user->getNom());
echo "<br>";
echo "Votre adresse : " . htmlspecialchars($user->getPrenom());
echo "<br>";
echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon compte</a>';



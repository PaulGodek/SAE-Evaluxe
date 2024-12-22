<?php
/** @var Administrateur $user */
use App\GenerateurAvis\Modele\DataObject\Administrateur;

$login =$user->getAdministrateur()->getLogin();
$loginURL=urlencode($login);
echo "Votre login : " . htmlspecialchars( $login);
echo"<br>";
echo "Votre adresse mail : ".htmlspecialchars($user->getAdresseMail());
echo' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login='.$loginURL.'" >Modifier mon compte</a>';


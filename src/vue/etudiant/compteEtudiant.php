
<link rel="stylesheet" type="text/css" href="../ressources/css/account.css">

<?php

/** @var Etudiant $user */
/** @var Array $nomPrenom */

use App\GenerateurAvis\Modele\DataObject\Etudiant;

$login = $user->getUtilisateur()->getLogin();

$loginURL = urlencode($login);
echo '<div class="infosCompte">';
echo "<h2> Vos informations </h2>";
echo "<p><span class='sousTitre'> Votre login : </span>" . htmlspecialchars($login)."</p>";
echo "<p><span class='sousTitre'> Votre nom : </span>" . htmlspecialchars($nomPrenom["Nom"])."</p>";
echo "<p><span class='sousTitre'>Votre prénom : </span>" . htmlspecialchars($nomPrenom["Prenom"])."</p>";
echo "<p><span class='sousTitre'>Votre code unique : </span>" . htmlspecialchars($user->getCodeUnique())."</p>";
echo "<p><span class='sousTitre'>Votre code Nip : </span>" . htmlspecialchars($user->getCodeNip())."</p>";


echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon mot de passe</a>';
echo ' <a class="button" href="controleurFrontal.php?controleur=etudiant&action=genererAvisPdf" >Générer mon pdf</a>';

echo "</div>";


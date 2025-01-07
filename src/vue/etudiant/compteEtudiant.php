<?php

/** @var Etudiant $user */
/** @var Array $nomPrenom */

use App\GenerateurAvis\Modele\DataObject\Etudiant;

$login = $user->getUtilisateur()->getLogin();

$loginURL = urlencode($login);
echo "Votre login : " . htmlspecialchars($login);
echo "<br>";
echo "Votre nom : " . htmlspecialchars($nomPrenom["Nom"]);
echo "<br>";
echo "Votre prénom : " . htmlspecialchars($nomPrenom["Prenom"]);
echo "<br>";
echo "Votre code unique : " . htmlspecialchars($user->getCodeUnique());
echo "<br>";
echo "Votre code Nip : " . htmlspecialchars($user->getCodeNip());
echo "<br>";
echo ' <a class="button" href="controleurFrontal.php?controleur=Utilisateur&action=afficherFormulaireMiseAJour&login=' . $loginURL . '" >Modifier mon compte</a>';
echo ' <a class="button" href="controleurFrontal.php?controleur=Etudiant&action=genererAvisPdf" >Télécharger l\'avis pour l\'enseignement supérieur</a>';



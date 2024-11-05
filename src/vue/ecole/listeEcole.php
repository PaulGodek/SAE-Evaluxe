<?php

use App\GenerateurAvis\Controleur\ControleurEcole;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

$login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
if($login === null){
    ControleurEcole::afficherErreur("Veuillez vous connecter avant");
    exit();
}
$utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
$admin = $utilisateur->getType() == "administrateur";

if (!$admin) {
    ControleurEcole::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
    exit();
}

echo "<h2>Liste des écoles</h2> 
    <p><a href='controleurFrontal.php?controleur=ecole&action=afficherListe'>  Trier par validation  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParNom'>  Trier par nom  </a>&emsp; 
       <a href='controleurFrontal.php?controleur=ecole&action=afficherListeEcoleOrdonneParVille'>  Trier par ville  </a></p> 
<ul>";

/** @var Ecole[] $ecoles */
foreach ($ecoles as $ecole) {
    $nomHTML = htmlspecialchars($ecole->getNom());
    $loginURL = rawurlencode($ecole->getLogin());

    if (!$ecole->isEstValide()) {
        echo '<li><p>L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a> 
                  &ensp; <a href="controleurFrontal.php?controleur=ecole&action=valider&login=' . $loginURL . '">Valider</a> </p></li>';
    } else {
        echo '<li><p>L\'école <a href="controleurFrontal.php?action=afficherDetail&login=' . $loginURL . '">' . $nomHTML . '</a></p></li>';
    }
}

echo "</ul>";

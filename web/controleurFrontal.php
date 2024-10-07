<?php

use App\GenerateurAvis\Controleur\ControleurEcole;
use App\GenerateurAvis\Controleur\ControleurEtudiant;
use App\GenerateurAvis\Controleur\ControleurUtilisateur;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// initialisation en activant l'affichage de débogage
$chargeurDeClasse = new App\GenerateurAvis\Lib\Psr4AutoloaderClass(false);
$chargeurDeClasse->register();
// enregistrement d'une association "espace de nom" → "dossier"
$chargeurDeClasse->addNamespace('App\GenerateurAvis', __DIR__ . '/../src');

// On récupère l'action passée dans l'URL

if (isset($_GET["controleur"])) {
    $controleur = $_GET["controleur"];
} else {
    $controleur = "utilisateur";
}


$nomDeClasseControleur = "App\GenerateurAvis\Controleur\Controleur" . ucfirst($controleur);


// On récupère l'action passée dans l'URL
if (class_exists($nomDeClasseControleur)) {
    $controllerInstance = new $nomDeClasseControleur();

    if (isset($_GET["action"])) {
        $action = $_GET['action'];

        if (in_array($action, get_class_methods($nomDeClasseControleur))) {

            $controllerInstance->$action();
        } else {
            $controllerInstance::afficherErreur(" L'action n'est pas possible" . $nomDeClasseControleur);
        }
    } else {
        $controllerInstance::afficherListe();
    }
} else {
    ControleurUtilisateur::afficherErreur(" Ce controleur n'existe pas ");

}
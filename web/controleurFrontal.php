<?php

use App\GenerateurAvis\Controleur\ControleurEcole;
use App\GenerateurAvis\Controleur\ControleurUtilisateur;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// initialisation en activant l'affichage de débogage
$chargeurDeClasse = new App\GenerateurAvis\Lib\Psr4AutoloaderClass(false);
$chargeurDeClasse->register();
// enregistrement d'une association "espace de nom" → "dossier"
$chargeurDeClasse->addNamespace('App\GenerateurAvis', __DIR__ . '/../src');

// On récupère l'action passée dans l'URL
if (isset($_GET["action"])) {
    $listeFonction = get_class_methods(ControleurUtilisateur::class); // Ou alors 'App\Covoiturage\Controleur\ControleurUtilisateur'
    if ($listeFonction != null && in_array($_GET["action"], $listeFonction)) {
        $action = $_GET["action"];
        // Appel de la méthode statique $action de ControleurUtilisateur
        ControleurUtilisateur::$action();
    }
    else {
        $listeFonction = get_class_methods(ControleurEcole::class);
        
        if ($listeFonction != null && in_array($_GET["action"], $listeFonction)) {

            $action = $_GET["action"];
            // Appel de la méthode statique $action de ControleurUtilisateur
            ControleurEcole::$action();
        }
        else {
            ControleurUtilisateur::afficherErreur("Cette action n'existe pas");
        }
    }
} else {
    ControleurUtilisateur::afficherListe();
}
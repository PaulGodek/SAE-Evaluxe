<?php

use App\Covoiturage\Controleur\ControleurUtilisateur;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// initialisation en activant l'affichage de débogage
$chargeurDeClasse = new App\Covoiturage\Lib\Psr4AutoloaderClass(false);
$chargeurDeClasse->register();
// enregistrement d'une association "espace de nom" → "dossier"
$chargeurDeClasse->addNamespace('App\Covoiturage', __DIR__ . '/../src');

// On récupère l'action passée dans l'URL
if (isset($_GET["action"])) {
    $listeFonction = get_class_methods('App\Covoiturage\Controleur\ControleurUtilisateur'); // Ou simplement ControleurUtilisateur::class
    if ($listeFonction != null && in_array($_GET["action"], $listeFonction)) {
        $action = $_GET["action"];
        // Appel de la méthode statique $action de ControleurUtilisateur
        ControleurUtilisateur::$action();
    }
    else {
        ControleurUtilisateur::afficherErreur("Cette action n'existe pas");
    }
} else {
    ControleurUtilisateur::afficherListe();
}
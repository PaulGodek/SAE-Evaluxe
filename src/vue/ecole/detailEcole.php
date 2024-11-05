<?php
/** @var Ecole $ecole */
use App\GenerateurAvis\Controleur\ControleurEcole;
use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;

if (!ConnexionUtilisateur::estUtilisateur($ecole->getLogin())) {
    echo "Vous n'avez pas de droit d'accès pour cette page";
    return;
}

echo "L'école " . htmlspecialchars($ecole->getNom()) . " se trouve à " . htmlspecialchars($ecole->getVille()) . ".";

<?php

namespace App\GenerateurAvis\Controleur;

class ControleurGenerique
{
    protected static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

    public static function afficherErreur(string $messageErreur = " "): void
    {

        echo($messageErreur);
    }
}
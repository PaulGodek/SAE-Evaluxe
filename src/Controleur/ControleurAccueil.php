<?php
namespace App\GenerateurAvis\Controleur;

class ControleurAccueil extends ControleurGenerique {
    public static function afficherErreurAccueil(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, "accueil");
    }

    public static function afficherAccueil(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Accueil", "cheminCorpsVue" => "siteweb/accueil.php"]);
    }

}

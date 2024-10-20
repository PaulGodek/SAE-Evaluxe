<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;

class ControleurConnexion
{
    public static function afficherConnexionAdministrateur(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionAdministrateur.php', "titre" => "Connexion Administrateur"]);
    }

    public static function afficherPreference(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/preference.php', "titre" => "Préférences"]);
    }

    public static function afficherErreur(string $message): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $message, "titre" => "Erreur"]);
    }

    public static function afficherConnexionEtudiant(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionEtudiant.php', "titre" => "Connexion Étudiant"]);
    }

    public static function afficherConnexionEcole(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionEcole.php', "titre" => "Connexion École"]);
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}

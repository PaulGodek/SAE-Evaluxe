<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;

class ControleurConnexion extends ControleurGenerique
{
    public static function afficherErreurConnexion(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, "connexion");
    }

    public static function afficherPreference(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/preference.php', "titre" => "Préférences"]);
    }

    public static function afficherConnexionAdministrateur(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionAdministrateur.php', "titre" => "Connexion Administrateur"]);
    }

    public static function afficherConnexionEtudiant(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionEtudiant.php', "titre" => "Connexion Étudiant"]);
    }

    public static function afficherConnexionEcole(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionEcole.php', "titre" => "Connexion École"]);
    }
    public static function afficherConnexionProfesseur(): void
    {
        self::afficherVue('vueGenerale.php', ["cheminCorpsVue" => 'siteweb/connexion/connexionProfesseur.php', "titre" => "Connexion Professeur"]);
    }

    public static function deconnecter(): void
    {
        ConnexionUtilisateur::deconnecter();
        // $utilisateurs = (new UtilisateurRepository())->recuperer();
        self::redirectionVersURL("success", "Déconnexion réussie", "afficherAccueil&controleur=accueil");
    }
}

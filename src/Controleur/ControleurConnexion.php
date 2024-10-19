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

    public static function connecter(): void
    {
        $role = $_GET['type'] ?? 'utilisateur';

        $login = $_GET['login'];
        $password = $_GET['password'];

        $user = (new UtilisateurRepository)->recupererParClePrimaire($login);
        if ($user && $password == $user->getPasswordHash()) {
            if ($user->getType() == $role) {

                $_SESSION['login'] = $login;
                $_SESSION['type'] = $role;

                switch ($role) {
                    case 'etudiant':
                        ControleurEtudiant::afficherDetail();
                        break;
                    case 'administrateur':
                        ControleurUtilisateur::afficherListe();
                        break;
                    case 'ecole':
                        $_SESSION['loginEcole'] = $login;
                        ControleurEcole::afficherEcole();
                        break;
                    default:
                        self::afficherErreur("Rôle non supporté");
                        break;
                }
            } else {
                self::afficherErreur("Rôle incorrect");
            }
        } else {
            self::afficherErreur("Login ou mot de passe incorrect");
        }
    }

    public static function deconnecter(): void
    {
        session_unset();
        session_destroy();

        header("Location: /sae3a-base/web/controleurFrontal.php?controleur=Accueil&action=afficher");
        exit();
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}

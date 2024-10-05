<?php
namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use TypeError;

class ControleurUtilisateur
{
    public static function afficherListe(): void
    {
        $utilisateurs = UtilisateurRepository::recupererUtilisateurs(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Liste des utilisateurs", "cheminCorpsVue" => "utilisateur/liste.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        try {
            $utilisateur = UtilisateurRepository::recupererUtilisateurParLogin($_GET['login']);
            if ($utilisateur == NULL) {
                self::afficherErreur("L'utilisateur de login {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["utilisateur" => $utilisateur, "titre" => "Détail de {$utilisateur->getPrenom()} {$utilisateur->getNom()}", "cheminCorpsVue" => "utilisateur/detail.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'utilisateur", "cheminCorpsVue" => "utilisateur/formulaireCreation.php"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        $user = new Utilisateur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        UtilisateurRepository::ajouter($user);
        $utilisateurs = UtilisateurRepository::recupererUtilisateurs();
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Création d'utilisateur", "cheminCorpsVue" => "utilisateur/utilisateurCree.php"]);
    }

    public static function afficherErreur(string $messageErreur = "") : void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "utilisateur/erreur.php"]);
    }

    public static function supprimer() : void
    {
        $login = $_GET["login"];
        UtilisateurRepository::supprimerParLogin($login);
        $utilisateurs = UtilisateurRepository::recupererUtilisateurs();
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "login" => $login, "titre" => "Suppression d'utilisateur", "cheminCorpsVue" => "utilisateur/utilisateurSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour() : void
    {
        $utilisateur = UtilisateurRepository::recupererUtilisateurParLogin($_GET['login']);
        self::afficherVue('vueGenerale.php', ["utilisateur" => $utilisateur, "titre" => "Formulaire de mise à jour d'utilisateur", "cheminCorpsVue" => "utilisateur/formulaireMiseAJour.php"]);
    }

    public static function mettreAJour() : void
    {
        $user = new Utilisateur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        UtilisateurRepository::mettreAJour($user);
        $utilisateurs = UtilisateurRepository::recupererUtilisateurs();
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "login" => $user->getLogin(), "titre" => "Suppression d'utilisateur", "cheminCorpsVue" => "utilisateur/utilisateurMisAJour.php"]);
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}
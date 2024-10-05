<?php
namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
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
                if ($utilisateur->getType()=="etudiant"){
                    $etudiant= EtudiantRepository::recupererEtudiantParLogin($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de l'étudiant {$etudiant->getPrenom()} {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
                }
                else if ($utilisateur->getType()=="ecole") {
                    $ecole=EcoleRepository::recupererEcoleParLogin($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de l'école {$ecole->getNom()} ", "cheminCorpsVue" => "ecole/detailEcole.php"]);
                }
                else{
                    self::afficherVue('vueGenerale.php',['utilisateur'=>$utilisateur,"cheminCorpsVue"=>"utilisateur/detail.php"]);
                }
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreationEcole(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'ecole", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerEcoleDepuisFormulaire(): void
    {
        $utilisateur = new Utilisateur($_GET["login"], $_GET["type"]);

        UtilisateurRepository::ajouter($utilisateur);
        $utilisateurs = UtilisateurRepository::recupererUtilisateurs();
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Création d'ecole", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }





    public static function afficherFormulaireCreationEtudiant(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'utilisateur", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function creerEtudiantDepuisFormulaire(): void
    {
        $utilisateur = new Utilisateur($_GET["login"], $_GET["type"]);

        UtilisateurRepository::ajouter($utilisateur);

        $etudiant= new Etudiant($_GET["login"],$_GET["nom"],$_GET["prenom"],$_GET["moyenne"]);
        EtudiantRepository::ajouter($etudiant);
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
        $utilisateur = new Utilisateur($_GET["login"], $_GET["type"]);


        if ($utilisateur->getType()=="etudiant") {
            $etudiant = EtudiantRepository::recupererEtudiantParLogin($utilisateur->getLogin());
            EtudiantRepository::mettreAJourEtudiant($etudiant);
            self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "login" => $etudiant->getLogin(), "titre" => "Mise à jour  de l'étudiant", "cheminCorpsVue" => "etudiant/etudiantMisAJour.php"]);

        }
        else if ($utilisateur->getType()=="ecole") {
            $ecole=EcoleRepository::recupererEcoleParLogin($utilisateur->getLogin());
            EcoleRepository::mettreAJourEcole($ecole);
            self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "login" => $ecole->getLogin(), "titre" => "Mise à jour  de l'école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);

        }


        $utilisateurs = UtilisateurRepository::recupererUtilisateurs();
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }
}


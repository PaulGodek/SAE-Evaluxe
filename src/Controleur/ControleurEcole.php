<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use TypeError;

class ControleurEcole extends ControleurGenerique
{
    public static function afficherEcole(): void
    {
        if (!ConnexionUtilisateur::estEcole()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $loginEcole = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $ecole = (new EcoleRepository)->recupererParClePrimaire($loginEcole);
        self::afficherVue('vueGenerale.php', [
            "ecole" => $ecole,
            "titre" => "Gestion de l'École: {$ecole->getNom()}",
            "cheminCorpsVue" => "ecole/pageEcole.php"
        ]);
    }


    public static function afficherListe(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParVille(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecoles = EcoleRepository::recupererEcolesOrdonneParVille(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        if (!self::verifierAdminConnectee()) return;

        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        if ($ecole == NULL) {
            self::afficherErreur("L'école {$_GET['login']} n'existe pas");
            return;
        }
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de {$ecole->getNom()}", "cheminCorpsVue" => "ecole/detailEcole.php"]);
    }


    /*public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], false);
        (new EcoleRepository)->ajouter($ecole);
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }*/

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "ecole/erreurEcole.php"]);
    }

    public static function supprimer(): void
    {
        $login = $_GET["login"];
        (new EcoleRepository)->supprimer($login);
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], $_GET["valide"]);
        (new EcoleRepository)->mettreAJour($ecole);
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);
    }

    public static function ajouterEtudiant(): void
    {
        $login = $_GET['login'];
        $codeUnique = $_GET['codeUnique'];
        if (!ConnexionUtilisateur::estEcole()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecole = (new EcoleRepository)->recupererParClePrimaire($login);

        $ecole->addFuturEtudiant($codeUnique);

        if ($ecole->saveFutursEtudiants()) {

            self::afficherVue('vueGenerale.php', [
                "titre" => "Ajout d'un étudiant",
                "message" => "L'étudiant avec le code {$codeUnique} a été ajouté avec succès.",
                "cheminCorpsVue" => "ecole/ecoleEtudiantAjoute.php",
                "codeUnique" => $codeUnique
            ]);
        } else {
            self::afficherErreur("Erreur lors de l'ajout de l'étudiant.");
        }
    }

    public static function valider(): void
    {
        if (!self::verifierAdminConnectee()) return;
        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);

        $ecole->setEstValide(true);

        (new EcoleRepository())->valider($ecole);
        $ecoles = (new EcoleRepository())->recuperer();

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Validation de compte ecole", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }

    private static function verifierAdminConnectee(): bool
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurUtilisateur::afficherErreur("Veuillez vous connecter d'abord.");
            return false;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page");
            return false;
        }
        return true;
    }
}
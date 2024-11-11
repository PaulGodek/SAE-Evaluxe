<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Lib\MessageFlash;

class ControleurEcole extends ControleurGenerique
{
    public static function afficherEcole(): void
    {
        if (!ConnexionUtilisateur::estEcole() && !ConnexionUtilisateur::estAdministrateur()) {

//            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $loginEcole = "";
        if (ConnexionUtilisateur::estEcole())
            $loginEcole = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        else if (ConnexionUtilisateur::estAdministrateur() && isset($_GET["loginEcole"]))
            $loginEcole = $_GET["loginEcole"];

        $ecole = (new EcoleRepository)->recupererParClePrimaire($loginEcole);
        self::afficherVue('vueGenerale.php', [
            "ecole" => $ecole,
            "titre" => "Gestion de l'École : {$ecole->getNom()}",
            "cheminCorpsVue" => "ecole/pageEcole.php"
        ]);
    }


    public static function afficherListe(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParVille(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecoles = EcoleRepository::recupererEcolesOrdonneParVille(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;

        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        if ($ecole == NULL) {
//            self::afficherErreurEcole("L'école {$_GET['login']} n'existe pas");
            MessageFlash::ajouter("error","L'école {$_GET['login']} n'existe pas");
            return;
        }
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de {$ecole->getNom()}", "cheminCorpsVue" => "ecole/detailEcole.php"]);
    }


    public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], false);
        (new EcoleRepository)->ajouter($ecole);
//        $ecoles = (new EcoleRepository)->recuperer();
//        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
        MessageFlash::ajouter("success", "L'école a été créée avec succès.");
    }

    public static function afficherErreurEcole(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'ecole');
    }

    public static function supprimer(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $login = $_GET["login"];
        (new EcoleRepository)->supprimer($login);
        MessageFlash::ajouter("success","L'école a été supprimée avec succès");
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], $_GET["valide"]);
        (new EcoleRepository)->mettreAJour($ecole);
        MessageFlash::ajouter("success", "L'école a été mise à jour avec succès.");
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);
    }
    public static function ajouterEtudiant(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estEcole()) $peutChecker = true;
        if ($peutChecker) {
            $login = $_GET['login'];
            $codeUnique = $_GET['codeUnique'];
            $ecole = (new EcoleRepository)->recupererParClePrimaire($login);

            $ecole->addFuturEtudiant($codeUnique);

            if (!is_null(EtudiantRepository::recupererEtudiantParCodeUnique($codeUnique)))
                $ecole->addFuturEtudiant($codeUnique);
            else {
//                self::afficherErreurEcole("Ce code unique n'est associé à aucun étudiant.");
                MessageFlash::ajouter("error", "Ce code unique n'est associé à aucun étudiant.");
                return;
            }

            if ($ecole->saveFutursEtudiants()) {
                MessageFlash::ajouter("success", "L'étudiant avec le code {$codeUnique} a été ajouté avec succès.");

//                self::afficherVue('vueGenerale.php', [
//                    "titre" => "Ajout d'un étudiant",
//                    "message" => "L'étudiant avec le code {$codeUnique} a été ajouté avec succès.",
//                    "cheminCorpsVue" => "ecole/ecoleEtudiantAjoute.php",
//                    "codeUnique" => $codeUnique
//                ]);
            } else {
//                self::afficherErreurEcole("Erreur lors de l'ajout de l'étudiant.");
                MessageFlash::ajouter("error","Erreur lors de l'ajout de l'étudiant");
            }
        } else {
//             self::afficherErreurEcole("Vous n'avez pas l'autorisation de réaliser cette action.");
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
        }
    }

    public static function valider(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);
        if (is_null($ecole)) {
//            self::afficherErreurEcole("Cette école n'existe pas.");
            MessageFlash::ajouter("error", "Cette école n'existe pas.");
            return;
        }

        $ecole->setEstValide(true);
        MessageFlash::ajouter("success", "L'école a été validée avec succès.");
        (new EcoleRepository())->valider($ecole);
        $ecoles = (new EcoleRepository())->recuperer();

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Validation de compte ecole", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }
}
<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use TypeError;

class ControleurEcole extends ControleurGenerique
{
    public static function afficherEcole(): void
    {
        $loginEcole = ConnexionUtilisateur::getLoginUtilisateurConnecte();

        if (!ConnexionUtilisateur::estEcole($loginEcole)) {
            self::afficherErreur("Veuillez vous connecter d'abord.");
            return;
        }

        $ecole = (new EcoleRepository)->recupererParClePrimaire($loginEcole);
        self::afficherVue('vueGenerale.php', [
            "ecole" => $ecole,
            "titre" => "Gestion de l'École: {$ecole->getNom()}",
            "cheminCorpsVue" => "ecole/pageEcole.php"
        ]);
    }


    public static function afficherListe(): void
    {
        $ecoles = (new EcoleRepository)->recuperer(); //appel au modèle pour gérer la BD

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParVille(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParVille(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        try {
            $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
            if ($ecole == NULL) {
                self::afficherErreur("L'école  {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de {$ecole->getNom()}", "cheminCorpsVue" => "ecole/detailEcole.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerDepuisFormulaire(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], false);
        (new EcoleRepository)->ajouter($ecole);
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }

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
        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"]);
        (new EcoleRepository)->mettreAJour($ecole);
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);
    }

    public static function ajouterEtudiant(): void
    {
        $login = $_GET['login'];
        $codeUnique = $_GET['codeUnique'];

        $ecole = (new EcoleRepository)->recupererParClePrimaire($login);

        if ($ecole === null) {
            self::afficherErreur("L'école avec le login {$login} n'existe pas.");
            return;
        }

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

    public static function valider()
    {

        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);

        $ecole->setEstValide(true);

        (new EcoleRepository())->valider($ecole);
        $ecoles = (new EcoleRepository())->recuperer();

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Validation de compte ecole", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }

}
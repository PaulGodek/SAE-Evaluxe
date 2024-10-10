<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use TypeError;

class ControleurEcole
{
    public static function afficherEcole(): void
    {
        $loginEcole = $_SESSION['loginEcole'] ?? null;

        if ($loginEcole) {
            $ecole = EcoleRepository::recupererEcoleParLogin($loginEcole);
            if (!$ecole) {
                self::afficherErreur("L'école n'existe pas.");
                return;
            }
            self::afficherVue('vueEcole.php', [
                "ecole" => $ecole,
                "titre" => "Gestion de l'École: {$ecole->getNom()}",
                "cheminCorpsVue" => "ecole/pageEcole.php"
            ]);
        } else {
            self::afficherErreur("Veuillez vous connecter d'abord.");
        }
    }


    public static function afficherListe(): void
    {
        $ecoles = EcoleRepository::recupererEcoles(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParAdresse(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParAdresse(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        try {
            $ecole = EcoleRepository::recupererEcoleParLogin($_GET['login']);
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
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"]);
        EcoleRepository::ajouter($ecole);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "ecole/erreurEcole.php"]);
    }

    public static function supprimer(): void
    {
        $login = $_GET["login"];
        EcoleRepository::supprimerEcoleParLogin($login);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        $ecole = EcoleRepository::recupererEcoleParLogin($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"]);
        EcoleRepository::mettreAJourEcole($ecole);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);
    }

    private static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

    public static function ajouterEtudiant(): void
    {
        $login = $_GET['login'];
        $codeUnique = $_GET['codeUnique'];

        $ecole = EcoleRepository::recupererEcoleParLogin($login);

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


}
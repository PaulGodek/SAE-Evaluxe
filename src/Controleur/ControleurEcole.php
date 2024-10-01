<?php
namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use TypeError;

class ControleurEcole
{
    public static function afficherListeEcole(): void
    {
        $ecoles = EcoleRepository::recupererEcoles(); //appel au modèle pour gérer la BD
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParAdresse(): void
    {
        $ecoles = EcoleRepository::recupererEcolesOrdonneParAdresse(); //appel au modèle pour gérer la BD
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetailEcole(): void
    {
        try {
            $ecole = EcoleRepository::recupererEcoleParNom($_GET['nom']);
            if ($ecole == NULL) {
                self::afficherErreurEcole("L'école  {$_GET['nom']} n'existe pas");
            } else {
                self::afficherVueEcole('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de {$ecole->getNom()}", "cheminCorpsVue" => "ecole/detailEcole.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreurEcole("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreationEcole(): void
    {
        self::afficherVueEcole('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerDepuisFormulaireEcole(): void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"]);
        EcoleRepository::ajouter($ecole);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }

    public static function afficherErreurEcole(string $messageErreur = "") : void
    {
        self::afficherVueEcole('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "ecole/erreurEcole.php"]);
    }

    public static function supprimerEcole() : void
    {
        $login = $_GET["login"];
        EcoleRepository::supprimerParLogin($login);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJourEcole() : void
    {
        $ecole = EcoleRepository::recupererEcoleParNom($_GET['login']);
        self::afficherVueEcole('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJourEcole() : void
    {
        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"]);
        EtudiantRepository::mettreAJour($ecole);
        $ecoles = EcoleRepository::recupererEcoles();
        self::afficherVueEcole('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/ecoleMisAJour.php"]);
    }

    private static function afficherVueEcole(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

}
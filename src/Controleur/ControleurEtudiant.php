<?php
namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use TypeError;

class ControleurEtudiant
{
    public static function afficherListeEtudiant(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiants(); //appel au modèle pour gérer la BD
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParAdresse(): void
    {
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParMoyenne(); //appel au modèle pour gérer la BD
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeetudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetailEtudiant(): void
    {
        try {
            $etudiant = EtudiantRepository::recupererEtudiantParNom($_GET['nom']);
            if ($etudiant == NULL) {
                self::afficherErreurEtudiant("L'étudiant  {$_GET['nom']} n'existe pas");
            } else {
                self::afficherVueEtudiant('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreurEtudiant("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreationEtudiant(): void
    {
        self::afficherVueEtudiant('vueGenerale.php', ["titre" => "Formulaire de création de compte école", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function creerDepuisFormulaireEtudiant(): void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"],$_GET["prenom"], $_GET["moyenne"]);
        EtudiantRepository::ajouterEtudiant($etudiant);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Création de compte école", "cheminCorpsVue" => "etudiant/etudiantCree.php"]);
    }

    public static function afficherErreurEtudiant(string $messageErreur = "") : void
    {
        self::afficherVueEtudiant('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "etudiant/erreurEtudiant.php"]);
    }

    public static function supprimerEtudiant() : void
    {
        $login = $_GET["login"];
        EtudiantRepository::supprimerEtudiantParLogin($login);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJourEtudiant() : void
    {
        $etudiant = EtudiantRepository::recupererEtudiantParNom($_GET['login']);
        self::afficherVueEtudiant('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
    }

    public static function mettreAJourEtudiant() : void
    {
        $etudiant = new Etudiant($_GET["login"], $_GET["nom"],$_GET["prenom"], $_GET["moyenne"]);
        EtudiantRepository::mettreAJourEtudiant($etudiant);
        $etudiants = EtudiantRepository::recupererEtudiants();
        self::afficherVueEtudiant('vueGenerale.php', ["etudiants" => $etudiants, "login" => $etudiant->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "etudiant/etudiantMisAJour.php"]);
    }

    private static function afficherVueEtudiant(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../vue/$cheminVue"; // Charge la vue
    }

}
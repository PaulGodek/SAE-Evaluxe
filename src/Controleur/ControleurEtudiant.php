<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use Random\RandomException;
use TypeError;

class ControleurEtudiant extends ControleurGenerique
{
    public static function afficherErreurEtudiant(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, "etudiant");
    }

    public static function afficherListe(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            $etudiants = (new EtudiantRepository)->recuperer(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
        }
    }

    /**
     * @throws RandomException
     */
    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
        }
    }

    public static function afficherListeEtudiantOrdonneParPrenom(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParPrenom(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
        }
    }

    public static function afficherListeEtudiantOrdonneParParcours(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParParcours(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
        }
    }

    public static function afficherDetail(): void
    {
        if (!isset($_GET["login"])) self::afficherErreurEtudiant("Le login n'est pas renseigné");
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estEtudiant() && strcmp(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $_GET["login"]) === 0) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            try {
                $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
                if ($etudiant == NULL) {
                    self::afficherErreurEtudiant("L'étudiant  {$_GET['login']} n'existe pas");
                } else {
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de $nomPrenom", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
                }
            } catch (TypeError $e) {
                self::afficherErreurEtudiant("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
            }
        } else {
            self::afficherErreurEtudiant("Vous n'avez pas les autorisations pour réaliser cette action.");
        }
    }

    public static function afficherDetailEtudiantParCodeUnique(): void
    {
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (!isset($_GET["codeUnique"])) self::afficherErreurEtudiant("Le code unique n'est pas valide");
        if (ConnexionUtilisateur::estEtudiant() && strcmp(EtudiantRepository::getCodeUniqueEtudiantConnecte(), $_GET["codeUnique"]) === 0) $peutChecker = true;
        if (ConnexionUtilisateur::estEcole() && in_array($_GET["codeUnique"], (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte())->getFutursEtudiants())) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            try {
                $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($_GET['codeUnique']);
                if ($etudiant == NULL) {
                    self::afficherErreurEtudiant("L'étudiant  {$_GET['codeUnique']} n'existe pas");
                } else {
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de $nomPrenom", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
                }
            } catch (TypeError $e) {
                self::afficherErreurEtudiant("Quelque chose ne marche pas, voila l'erreur : {$e->getMessage()}");
            }
        }
        else {
            self::afficherErreurEtudiant("Vous n'avez pas l'autorisation de réaliser cette action.");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte étudiant", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function supprimer(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $login = $_GET["login"];
        (new EtudiantRepository)->supprimer($login);
        $etudiants = (new EtudiantRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $login, "titre" => "Suppression de compte étudiant", "cheminCorpsVue" => "etudiant/etudiantSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour de compte étudiant", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function mettreAJour(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiant = new Etudiant($_GET["login"], $_GET["etudid"]);
        (new EtudiantRepository)->mettreAJour($etudiant);
        $etudiants = (new EtudiantRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "login" => $etudiant->getLogin(), "titre" => "Mise a jour de compte étudiant", "cheminCorpsVue" => "etudiant/etudiantMisAJour.php"]);
    }
}
<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
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
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiants = (new EtudiantRepository)->recuperer(); //appel au modèle pour gérer la BD
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    /**
     * @throws RandomException
     */
    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParPrenom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParPrenom(); //appel au modèle pour gérer la BD
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParParcours(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParParcours(); //appel au modèle pour gérer la BD
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        if (!isset($_GET["login"])) {
            MessageFlash::ajouter("error", "Le login n'est pas renseigné");
            self::afficherErreurEtudiant(" ");
        }
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estEtudiant() && strcmp(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $_GET["login"]) === 0) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;
        if ($peutChecker) {
            try {
                $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
                if ($etudiant == NULL) {
                    self::afficherErreurEtudiant(" ");
                    MessageFlash::ajouter("error", "L'étudiant  {$_GET['login']} n'existe pas");
                } else {
                    $idEtudiant = $etudiant->getIdEtudiant();
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParIdEtudiant($idEtudiant);
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    $result = EtudiantRepository::recupererTousLesDetailsEtudiantParId($idEtudiant);

                    $etudiantInfo = $result['info'];
                    $etudiantDetailsPerSemester = $result['details'];

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => "Détail de $nomPrenom",
                        "informationsPersonelles" => $etudiantInfo,
                        "informationsParSemestre" => $etudiantDetailsPerSemester,
                        "idEtudiant" => $idEtudiant,
                        "codeUnique" => $etudiant->getCodeUnique(),
                        "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
                }
            } catch (TypeError $e) {
                self::afficherErreurEtudiant(" ");
                MessageFlash::ajouter("error", "Jsp ce qu'il s'est passé dsl");

            }
        } else {
            self::afficherErreurEtudiant(" ");
            MessageFlash::ajouter("warning", "Vous n'avez pas les autorisations pour réaliser cette action.");
        }
    }

    public static function afficherDetailEtudiantParCodeUnique(): void
    {
        if (!isset($_GET["codeUnique"])) {
            MessageFlash::ajouter("error", "Le code unique n'est pas valide");
            self::afficherErreurEtudiant(" ");
            return;
        }
        $peutChecker = false;
        if (ConnexionUtilisateur::estAdministrateur()) $peutChecker = true;
        if (ConnexionUtilisateur::estEtudiant() && strcmp(EtudiantRepository::getCodeUniqueEtudiantConnecte(), $_GET["codeUnique"]) === 0) $peutChecker = true;
        if (ConnexionUtilisateur::estEcole() && in_array($_GET["codeUnique"], (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte())->getFutursEtudiants())) $peutChecker = true;
        if (ConnexionUtilisateur::estProfesseur()) $peutChecker = true;

        if ($peutChecker) {
            try {
                $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($_GET['codeUnique']);
                if ($etudiant == NULL) {
                    MessageFlash::ajouter("error", "L'étudiant  {$_GET['codeUnique']} n'existe pas");
                    self::afficherErreurEtudiant(" ");
                } else {
                    $idEtudiant = $etudiant->getIdEtudiant();
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParIdEtudiant($idEtudiant);
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    $result = EtudiantRepository::recupererDetailsEtudiantParId($idEtudiant);

                    $etudiantInfo = $result['info'];
                    $etudiantDetailsPerSemester = $result['details'];

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => "Détail de $nomPrenom",
                        "informationsPersonelles" => $etudiantInfo,
                        "informationsParSemestre" => $etudiantDetailsPerSemester,
                        "idEtudiant" => $idEtudiant,
                        "codeUnique" => $etudiant->getCodeUnique(),
                        "cheminCorpsVue" => "etudiant/detailEtudiantPourEcoles.php"]);
                }
            } catch (TypeError $e) {
                MessageFlash::ajouter("error", "Quelque chose ne marche pas, voila l'erreur : {$e->getMessage()}");
                self::afficherErreurEtudiant(" ");
            }
        } else {
            MessageFlash::ajouter("warning", "Vous n'avez pas l'autorisation de réaliser cette action.");
            self::afficherErreurEtudiant(" ");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte étudiant", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $login = $_GET["login"];
        (new EtudiantRepository)->supprimer($login);
        MessageFlash::ajouter("success", "Le compte de login " . htmlspecialchars($login) . " a bien été supprimé");
        $etudiants = (new EtudiantRepository)->recuperer();
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php', ["listeNomPrenom" => $listeNomPrenom, "etudiants" => $etudiants, "login" => $login, "titre" => "Suppression de compte étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour de compte étudiant", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function mettreAJour(): void //definetely not the best but works for now
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $login = $_GET["login"];
        $repository = new EtudiantRepository();
        $etudiantExistant = $repository->recupererParClePrimaire($login);
        if (isset($_GET["etudid"])) {
            $etudiantExistant->setIdEtudiant($_GET["etudid"]);
        }
        //$etudiantChangee = new Etudiant($user, $_GET["etudid"], $etudiantExistant->getDemandes(), $etudiantExistant->getCodeUnique());

        $repository->mettreAJour($etudiantExistant);
        MessageFlash::ajouter("success", "Le compte de login " . htmlspecialchars($login) . " a bien été mis à jour");
        //$etudiants = (new EtudiantRepository)->recuperer();
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        self::afficherVue('vueGenerale.php',
            ["etudiants" => $etudiants,
                "listeNomPrenom" => $listeNomPrenom,
                "titre" => "Liste des etudiants",
                "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue

    }

    public static function afficherResultatRechercheEtudiant(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiants = EtudiantRepository::rechercherEtudiantParLogin($_GET['reponse']);
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        self::afficherVue("vueGenerale.php", ["listeNomPrenom" => $listeNomPrenom,"ecole" => $ecole, "etudiants" => $etudiants, "titre" => "Résultat recherche étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }


    public static function demander(): void
    {
        if (!ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }

        $etudiant = (new EtudiantRepository())->recupererParClePrimaire($_GET["login"]);
        $nom = (new EcoleRepository())->recupererParClePrimaire($_GET["demandeur"])->getNom();


        if (is_null($etudiant)) {
            MessageFlash::ajouter("error", "Cette etudiant n'existe pas.");
            self::afficherErreurEtudiant(" ");
            return;
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $etudiant->addDemande($nom);


        if ($etudiant->faireDemande()) {
            MessageFlash::ajouter("success", "La demande d'accès a bien été envoyée.");
        }

        $etudiants = (new EtudiantRepository())->recuperer();
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }

        self::afficherVue('vueGenerale.php', ["listeNomPrenom" => $listeNomPrenom,"ecole" => $ecole, "etudiants" => $etudiants, "titre" => "Demande d'accès aux infos d'un étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }

    public static function supprimerDemande(): void
    {
        if (!ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiant = (new EtudiantRepository())->recupererParClePrimaire($_GET["login"]);
        $nom = (new EcoleRepository())->recupererParClePrimaire($_GET["demandeur"])->getNom();
        if (is_null($etudiant)) {
            MessageFlash::ajouter("error", "Cette etudiant n'existe pas.");
            self::afficherErreurEtudiant(" ");
            return;
        }

        if ($etudiant->removeDemande($nom)) {
            MessageFlash::ajouter("success", "La demande a bien été supprimée.");
        }
        $ecole = (new EcoleRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $etudiants = (new EtudiantRepository())->recuperer();
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
            $listeNomPrenom[] = $nomPrenom;
        }

        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Demande d'accès aux infos d'un étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }
}
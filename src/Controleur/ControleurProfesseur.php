<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use Random\RandomException;
use TypeError;

class ControleurProfesseur extends ControleurGenerique
{
    public static function afficherErreurProfesseur(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'professeur');
    }

    public static function afficherListe(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $professeurs = (new ProfesseurRepository())->recuperer(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeProfesseurOrdonneParPrenom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            if (!ConnexionUtilisateur::estProfesseur()) {
                self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
                return;
            }
        }
        try {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            if ($professeur == NULL) {
                MessageFlash::ajouter("error", "Le professeur {$_GET['login']} n'existe pas");
                self::afficherErreurProfesseur(" ");
            } else {
                self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Détail de {$professeur->getNom()}", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreurProfesseur(" ");
            MessageFlash::ajouter("warning", $e->getMessage());
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);

    }

    public static function creerDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["login"])) {
            MessageFlash::ajouter("error","Le login n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        } elseif (!isset($_GET["nom"])) {
            MessageFlash::ajouter("error","Le nom n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        } elseif (!isset($_GET["prenom"])) {
            MessageFlash::ajouter("error","Le prénom n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        $professeur = new professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->ajouter($professeur);
        MessageFlash::ajouter("success", "Le compte professeur a bien été créé !");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création de compte professeur", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["login"])) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $login = $_GET["login"];
        (new ProfesseurRepository)->supprimer($login);
        MessageFlash::ajouter("success", "Le compte de login " . htmlspecialchars($login) . " a bien été supprimé");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $login, "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["login"])) {
            MessageFlash::ajouter("error","Le login n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Formulaire de mise à jour de compte professeur", "cheminCorpsVue" => "professeur/formulaireMiseAJourProfesseur.php"]);

    }

    public static function mettreAJour(): void
    {

        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $professeurExistant = (new ProfesseurRepository())->recupererParClePrimaire($_GET['login']);




        $mdp = $_GET['nvmdp'] ?? '';
        $mdp2 = $_GET['nvmdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning","Les mots de passe ne correspondent pas");
            self::afficherVue('vueGenerale.php', ["ecole" => $professeurExistant, "titre" => "Formulaire de mise à jour d'un professeur", "cheminCorpsVue" => "professeur/formulaireMiseAJourProfesseur.php"]);
            return;
        }

        $userexistant = (new UtilisateurRepository())->recupererParClePrimaire($_GET['login']);
        if($_GET["nvmdp"]==''){
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), $userexistant->getPasswordHash());
        }else {
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), MotDePasse::hacher($_GET["nvmdp"]));
        }        (new UtilisateurRepository)->mettreAJour($user);
        $prof = new Professeur($user, $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->mettreAJour($prof);

        if (ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("success", "L'école a été mise à jour avec succès.");
            $professeurs = (new ProfesseurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste Professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
        }else{
            MessageFlash::ajouter("success", "Votre compte a été mis à jour avec succès.");
            $prof=(new ProfesseurRepository())->recupererParClePrimaire($_GET['login']);
            self::afficherVue('vueGenerale.php', [
                "user" => $prof,
                "titre" => "Compte Ecole",
                "cheminCorpsVue" => "professeur/compteProfesseur.php"
            ]);
        }
    }

    public static function creerProfesseurDepuisFormulaire(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning", "Les mots de passes ne correspondent pas");
            self::afficherErreurProfesseur(" ");
            return;
        }
        $utilisateur = self::construireDepuisFormulaire($_GET);
        (new UtilisateurRepository)->ajouter($utilisateur);


        $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->ajouter($professeur);
        MessageFlash::ajouter("success", "Le compte professeur a bien été créé !");
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création du professeur", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
    }

    public static function afficherResultatRechercheProfesseur(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $professeurs = ProfesseurRepository::rechercherProfesseur($_GET['reponse']);
        self::afficherVue("vueGenerale.php", ["professeurs" => $professeurs, "titre" => "Résultat recherche professeur", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
    }

    public static function afficherFormulaireAvisEtudiant(): void {
        if (!ConnexionUtilisateur::estProfesseur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["loginEtudiant"])) {
            MessageFlash::ajouter("error","Le login de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        if (!isset($_GET["code_nip"])) {
            MessageFlash::ajouter("error","Le login de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        $nomPrenomArray = EtudiantRepository::getNomPrenomParCodeNip(rawurldecode($_GET["code_nip"]));
        if (is_null($nomPrenomArray)) {
            $nomPrenomArray = array(
                "Nom" => "NomInconnu",
                "Prenom" => "PrenomInconnu"
            );
        }
        $avis = ProfesseurRepository::getAvis($_GET["loginEtudiant"], ConnexionUtilisateur::getLoginUtilisateurConnecte());
        self::afficherVue("vueGenerale.php", ["avis" => $avis, "nomPrenomArray" => $nomPrenomArray, "loginEtudiant" => $_GET["loginEtudiant"], "titre" => "Formulaire d'avis d'étudiant", "cheminCorpsVue" => "professeur/formulaireAvisPersonnaliseEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function publierAvisEtudiant(): void {
        if (!ConnexionUtilisateur::estProfesseur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["loginEtudiant"])) {
            MessageFlash::ajouter("error","Le login de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        if (!isset($_GET["avis"])) {
            MessageFlash::ajouter("error","L'avis de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        if (!isset($_GET["avisDejaSet"])) {
            MessageFlash::ajouter("error","L'avis de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        if (!isset($_GET["ecoleIngenieur"])) {
            MessageFlash::ajouter("error","L'avis pour l'école d'ingénieur n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        if (!isset($_GET["masterManagement"])) {
            MessageFlash::ajouter("error","L'avis pour le master en management n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }

        $loginConnecte = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $loginEtudiant = rawurldecode($_GET["loginEtudiant"]);
        $avis = rawurldecode($_GET["avis"]);
        $ecoleIngenieur = rawurldecode($_GET["ecoleIngenieur"]);
        $masterManagement = rawurldecode($_GET["masterManagement"]);

        if ($_GET["avisDejaSet"] === "1") {
            if (strcmp($avis, "") === 0) {
                ProfesseurRepository::supprimerAvis($loginEtudiant, $loginConnecte);
            } else {
                ProfesseurRepository::mettreAJourAvis($loginEtudiant, $loginConnecte, $avis, $ecoleIngenieur, $masterManagement);
            }
        } else {
            ProfesseurRepository::ajouterAvis($loginEtudiant, $loginConnecte, $avis, $ecoleIngenieur, $masterManagement);
        }

        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom();
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
            $listeNomPrenom[] = $nomPrenom;
        }
        MessageFlash::ajouter("success", "L'avis a bien été enregistré.");
        self::afficherVue("vueGenerale.php", ["etudiants" => $etudiants, "listeNomPrenom" => $listeNomPrenom, "titre" => "Avis publié", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }

    public static function afficherAvisProfesseurs() : void {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        if (!isset($_GET["login"])) {
            MessageFlash::ajouter("error","Le login de l'étudiant n'est pas renseigné");
            self::afficherErreurProfesseur("");
            return;
        }
        $avis = ProfesseurRepository::getToutAvis($_GET["login"]);
        $listeNomPrenom = array();
        if (!is_null($avis)) {
            foreach ($avis as $avisIndividuel) {
                $nomPrenom = ProfesseurRepository::getNomPrenomParIdProfesseur($avisIndividuel["loginProfesseur"]);
                $listeNomPrenom[$avisIndividuel["loginProfesseur"]] = $nomPrenom;
            }
        }
        self::afficherVue("vueGenerale.php", ["listeNomPrenom" => $listeNomPrenom, "avis" => $avis, "titre" => "Avis des Professeurs", "cheminCorpsVue" => "etudiant/avisProfesseurs.php"]);
    }
}
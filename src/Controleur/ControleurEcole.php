<?php

namespace App\GenerateurAvis\Controleur;
require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use PHPMailer\PHPMailer\SMTP;
use Random\RandomException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ControleurEcole extends ControleurGenerique
{
    public static function afficherErreurEcole(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'ecole');
    }

    public static function afficherEcole(): void
    {
        if (!ConnexionUtilisateur::estEcole() && !ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $loginEcole = "";
        if (ConnexionUtilisateur::estEcole()) {
            $loginEcole = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        } else if (ConnexionUtilisateur::estAdministrateur() && isset($_GET["loginEcole"])) {
            $loginEcole = $_GET["loginEcole"];
        }

        $ecoleRepository = new EcoleRepository();
        $ecole = $ecoleRepository->recupererParClePrimaire($loginEcole);
        if (!isset($ecole)) {
            self::afficherErreurEcole("Aucune école avec le login " . $loginEcole . " n'existe");
            return;
        }

        $futursEtudiants = $ecoleRepository::getFutursEtudiantsListe($loginEcole);
        self::afficherVue('vueGenerale.php', [
            "ecole" => $ecole,
            "futursEtudiants" => $futursEtudiants,
            "titre" => "Gestion de l'École : {$ecole->getNom()}",
            "cheminCorpsVue" => "ecole/pageEcole.php"
        ]);
    }

    public static function afficherListe(): void
    {

        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecoles = (new EcoleRepository)->recuperer();

        if (empty($ecoles)) {
            self::redirectionVersURL("warning", "Aucune école n'existe", "afficher&controleur=Accueil");
            return;
        }

        $etudiant = (new EtudiantRepository)->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        if (empty($ecoles)) {
            self::redirectionVersURL("warning", "Aucune école n'existe", "afficher&controleur=Accueil");
            return;
        }
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParVille(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecoles = EcoleRepository::recupererEcolesOrdonneParVille(); //appel au modèle pour gérer la BD
        if (empty($ecoles)) {
            self::redirectionVersURL("warning", "Aucune école n'existe", "afficher&controleur=Accueil");
            return;
        }
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()&& !ConnexionUtilisateur::estEcole()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        if ($ecole == NULL) {
            MessageFlash::ajouter("error", "L'école {$_GET['login']} n'existe pas");
            self::afficherErreurEcole(" ");
            return;
        }
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole,
            "titre" => "Détail de l'école {$ecole->getNom()}",
            "cheminCorpsVue" => "ecole/detailEcole.php"]);
    }

    public static function creerEcoleDepuisFormulaire(): void
    {
        /*if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }*/

        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            self::redirectionVersURL("warning", "Les mots de passes ne correspondent pas", "afficherFormulaireCreation&controleur=ecole");
//            self::afficherErreurEcole("Mots de passe distincts");
            return;
        }
        if (!isset($_GET["login"]) || !isset($_GET["nom"]) || !isset($_GET["adresse"]) || !isset($_GET["ville"]) || !isset($_GET["adresseMail"])) {
            self::redirectionVersURL("warning", "Compléter tous les champs", "afficherFormulaireCreation&controleur=ecole");
            return;
        }
        $login = $_GET["login"];
        $existingUser = (new UtilisateurRepository)->existeUtilisateurParLogin($login);

        if ($existingUser) {
            self::redirectionVersURL("warning", "Le login existe déjà. Veuillez en choisir un autre", "afficherFormulaireCreation&controleur=ecole");
            return;
        }

        ControleurEcole::creerDepuisFormulaire();
    }

    public static function creerDepuisFormulaire(): void
    {
        $utilisateur = new Utilisateur($_GET["login"], "universite", $_GET['mdp']);
        (new UtilisateurRepository)->ajouter($utilisateur);
        $ecole = new Ecole($utilisateur, $_GET["nom"], $_GET["adresse"], $_GET["ville"], $_GET["adresseMail"], false, []);
        (new EcoleRepository)->ajouter($ecole);
        //MessageFlash::ajouter("success", "L'école a été créée avec succès.");
        $ecoles = (new EcoleRepository)->recuperer();
        $data = [
            "nom" => $ecole->getNom(),
            "adresse" => $ecole->getAdresse(),
            "ville" => $ecole->getVille(),
            "login" => $utilisateur->getLogin(),
            "title" => "Un nouveau compte École a été créé :"
        ];

        self::sendEmail($data, "evaluxe2024@gmail.com", "Création de compte école");
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }

    private static function sendEmail(array $data, string $emailRecipient, string $subject): void
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "evaluxe.iutmontpellier@gmail.com";
            //$mail->Password = "wxkpmingdadommya";
            $mail->Password = "gmeyhmxdcymcfawn ";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom("evaluxe.iutmontpellier@gmail.com", "No Reply");
            $mail->addAddress($emailRecipient);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->setLanguage('fr');
            $mail->Subject = $subject;

            ob_start();
            extract([
                "nom" => $data["nom"],
                "adresse" => $data["adresse"],
                "ville" => $data["ville"],
                "login" => $data["login"],
                "dateCreation" => date('Y-m-d H:i:s'),
                "title" => $data["title"],
            ]);
            include __DIR__ . '/../vue/ecole/emailEcole.php';

            $mail->Body = ob_get_clean();
            $mail->send();

        } catch (Exception $e) {
            MessageFlash::ajouter("warning", "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            self::afficherErreurEcole("");
        }
    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $login = $_GET["login"];
        (new EcoleRepository)->supprimer($login);
        MessageFlash::ajouter("success", "L'école a été supprimée avec succès");
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $login, "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) { // Peut être amélioré à l'avenir pour permettre aux écoles aussi de se modifier
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEcole()) { // Peut être amélioré à l'avenir pour permettre aux écoles aussi de se modifier
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecoleExistant = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);




        $mdp = $_GET['nvmdp'] ?? '';
        $mdp2 = $_GET['nvmdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning","Les mots de passe ne correspondent pas");
            self::afficherVue('vueGenerale.php', ["ecole" => $ecoleExistant, "titre" => "Formulaire de mise à jour d'une école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
            return;
        }
        $userexistant = (new UtilisateurRepository())->recupererParClePrimaire($_GET['login']);
        if(($_GET["nvmdp"]=='')){
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), $userexistant->getPasswordHash());
        }else {
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), MotDePasse::hacher($_GET["nvmdp"]));
        }
        (new UtilisateurRepository)->mettreAJour($user);
        $ecole = new Ecole($user, $_GET["nom"], $_GET["adresse"], $_GET["ville"],$ecoleExistant->getAdresseMail(), $ecoleExistant->isEstValide(), $ecoleExistant->getFutursEtudiants(), $ecoleExistant->getAdresseMail());
        (new EcoleRepository)->mettreAJour($ecole);

        if (ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("success", "L'école a été mise à jour avec succès.");
            $ecoles = (new EcoleRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Mise à jour de compte école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
        }else{
            MessageFlash::ajouter("success", "Votre compte a été mis à jour avec succès.");
            $ecole=(new EcoleRepository())->recupererParClePrimaire($_GET['login']);
            self::afficherVue('vueGenerale.php', [
                "user" => $ecole,
                "titre" => "Compte Ecole",
                "cheminCorpsVue" => "ecole/compteEcole.php"
            ]);
        }
    }

    /**
     * @throws RandomException
     */
    public static function ajouterEtudiant(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEcole() && !ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $login = $_GET['login'];
        $codeUnique = $_GET['codeUnique'];
        $ecole = (new EcoleRepository)->recupererParClePrimaire($login);

        $ecole->addFuturEtudiant($codeUnique);

        if (!is_null(EtudiantRepository::recupererEtudiantParCodeUnique($codeUnique)))
            $ecole->addFuturEtudiant($codeUnique);
        else {
            MessageFlash::ajouter("error", "Ce code unique n'est associé à aucun étudiant.");
            self::afficherErreurEcole(" ");
            return;
        }

        if ($ecole->saveFutursEtudiants()) {
            self::redirectionVersURL("success", "L'étudiant a bien été ajouté", "afficherEcole&controleur=ecole");
        } else {
            MessageFlash::ajouter("error", "Erreur lors de l'ajout de l'étudiant");
            self::afficherErreurEcole(" ");
        }
    }

    public static function valider(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);
        if (is_null($ecole)) {
            MessageFlash::ajouter("error", "Cette école n'existe pas.");
            self::afficherErreurEcole(" ");
            return;
        }

        $ecole->setEstValide(true);
        MessageFlash::ajouter("success", "L'école a été validée avec succès.");
        (new EcoleRepository())->valider($ecole);
        $ecoles = (new EcoleRepository())->recuperer();
        $data = [
            "nom" => $ecole->getNom(),
            "adresse" => $ecole->getAdresse(),
            "ville" => $ecole->getVille(),
            "login" => $ecole->getUtilisateur()->getLogin(),
            "title" => "Votre compte a été validé!"
        ];
        self::sendEmail($data, $ecole->getAdresseMail(), "Votre compte été validé!");

        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Validation de compte ecole", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }

    public static function afficherFormulaireCreation(): void
    {
        /*if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }*/
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'ecole", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function afficherResultatRechercheEcole(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }
        $ecoles = EcoleRepository::rechercherEcole($_GET['nom']);
        self::afficherVue("vueGenerale.php", ["ecoles" => $ecoles, "titre" => "Résultat recherche école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
    }


    public static function accepterDemande(): void
    {
        if (!ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);
        if (is_null($ecole)) {
            MessageFlash::ajouter("error", "Cette école n'existe pas.");
            self::afficherErreurEcole(" ");
            return;
        }
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET["loginEtudiant"]);
        $ecole->addFuturEtudiant($etudiant->getCodeUnique());


        if ($ecole->saveFutursEtudiants()) {

            MessageFlash::ajouter("success", "Vous avez accepté la demande de l'école");
        } else {
            MessageFlash::ajouter("error", "Erreur lors de l'acceptation de partage");
            self::afficherErreurEcole(" ");
        }
        $etudiant->removeDemande($ecole->getNom());
        $ecoles = (new EcoleRepository())->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des demandes", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }

    public static function refuserDemande(): void
    {
        if (!ConnexionUtilisateur::estEtudiant()) {
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
            return;
        }

        $ecole = (new EcoleRepository())->recupererParClePrimaire($_GET["login"]);
        if (is_null($ecole)) {
            MessageFlash::ajouter("error", "Cette école n'existe pas.");
            self::afficherErreurEcole(" ");
            return;
        }
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET["loginEtudiant"]);

        $etudiant->removeDemande($ecole->getNom());


        $ecoles = (new EcoleRepository())->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des demandes", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }
}
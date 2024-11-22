<?php

namespace App\GenerateurAvis\Controleur;
require __DIR__ . '/../../bootstrap.php';

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\AbstractRepository;
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
            self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficherAccueil&controleur=Accueil");
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
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecoles = (new EcoleRepository)->recuperer();
        $etudiant = (new EtudiantRepository)->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "etudiant" => $etudiant, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParNom(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecoles = EcoleRepository::recupererEcolesOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEcoleOrdonneParVille(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecoles = EcoleRepository::recupererEcolesOrdonneParVille(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des ecoles", "cheminCorpsVue" => "ecole/listeEcole.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
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
//            self::afficherErreurUtilisateur("Mots de passe distincts");
            return;
        }

        ControleurEcole::creerDepuisFormulaire();
    }

    /**
     * @throws Exception
     */
    public static function creerDepuisFormulaire(): void
    {
        $utilisateur = new Utilisateur($_GET["login"], "universite", $_GET['mdp']);
        (new UtilisateurRepository)->ajouter($utilisateur);
        $ecole = new Ecole($utilisateur, $_GET["nom"], $_GET["adresse"], $_GET["ville"], false);
        (new EcoleRepository)->ajouter($ecole);
        MessageFlash::ajouter("success", "L'école a été créée avec succès.");
        $ecoles = (new EcoleRepository)->recuperer();
        $data = [
            "nom"=>$ecole->getNom(),
            "adresse"=>$ecole->getAdresse(),
            "ville"=>$ecole->getVille(),
            "login"=>$utilisateur->getLogin(),
        ];
        self::sendEmailToAdmin($data);
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Création de compte école", "cheminCorpsVue" => "ecole/ecoleCree.php"]);
    }

    private static function sendEmailToAdmin(array $data): void{
        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username="evaluxe.iutmontpellier@gmail.com";
            $mail->Password="ButInformatique";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom("evaluxe.iutmontpellier@gmail.com", "No Reply");
            $mail->addAddress("evaluxe2024@gmail.com");
            $mail->isHTML(true);
            $mail->Subject = "Création de compte école";

            $mailContent ="
        <h2>A new Ecole account was created:</h2>
            <p><strong>Ecole Name:</strong> {$data['nom']}</p>
            <p><strong>Login:</strong> {$data['login']}</p>
            <p><strong>Address:</strong> {$data['adresse']}</p>
            <p><strong>City:</strong> {$data['ville']}</p>
            <p><strong>Created on:</strong> " . date('Y-m-d H:i:s') . "</p>
        
        ";
            $mail->Body = $mailContent;
            $mail->send();
            echo 'Message has been sent';
        }catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    public static function supprimer(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
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
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
        self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour de compte école", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
    }

    public static function mettreAJour(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur()) { // Peut être amélioré à l'avenir pour permettre aux écoles aussi de se modifier
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }

        $ecole = new Ecole($_GET["login"], $_GET["nom"], $_GET["adresse"], $_GET["ville"], $_GET["valide"]);
        (new EcoleRepository)->mettreAJour($ecole);
        MessageFlash::ajouter("success", "L'école a été mise à jour avec succès.");
        $ecoles = (new EcoleRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "login" => $ecole->getUtilisateur()->getLogin(), "titre" => "Suppression de compte école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function ajouterEtudiant(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEcole() && !ConnexionUtilisateur::estEtudiant()) {
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
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
            self::afficherErreurEcole("Vous n'avez pas le droit à cette action");
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
            self::afficherErreurEcole("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $ecoles = EcoleRepository::rechercherEcole($_GET['nom']);
        self::afficherVue("vueGenerale.php", ["ecoles" => $ecoles, "titre" => "Résultat recherche école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
    }


    public static function accepterDemande(): void
    {


        if (!ConnexionUtilisateur::estEtudiant()) {
            self::afficherErreurEcole("Vous n'avez pas le droit à cette action");
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

        $ecoles = (new EcoleRepository())->recuperer();
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des demandes", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }

    public static function refuserDemande(): void
    {

        if (!ConnexionUtilisateur::estEtudiant()) {
            self::afficherErreurEcole("Vous n'avez pas le droit à cette action");
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
        self::afficherVue('vueGenerale.php', ["ecoles" => $ecoles, "titre" => "Liste des demandes", "cheminCorpsVue" => "ecole/listeEcole.php"]);

    }
}
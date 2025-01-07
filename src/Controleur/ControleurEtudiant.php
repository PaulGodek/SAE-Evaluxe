<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\AgregationRepository;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use Dompdf\Dompdf;
use Exception;
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
                    MessageFlash::ajouter("error", "L'étudiant {$_GET['login']} n'existe pas");
                } else {
                    $code_nip = $etudiant->getCodeNip();
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParCodeNip($code_nip);
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    $result = EtudiantRepository::recupererTousLesDetailsEtudiantParCodeNip($code_nip);


                    $etudiantInfo = $result['info'];
                    $etudiantDetailsPerSemester = $result['details'];
                    $agregations = (new AgregationRepository)->getAgregationDetailsByLogin(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                    $agregationsResults = AgregationRepository::calculateAgregationNotes($agregations, $code_nip);

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => "Détail de $nomPrenom",
                        "informationsPersonelles" => $etudiantInfo,
                        "informationsParSemestre" => $etudiantDetailsPerSemester,
                        "code_nip" => $code_nip,
                        "codeUnique" => $etudiant->getCodeUnique(),
                       "agregations" => $agregationsResults,
                        "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);

                }
            } catch (TypeError $e) {
                self::afficherErreurEtudiant(" ");
                MessageFlash::ajouter("error", "Erreur Inconnue");

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
                    $code_nip = $etudiant->getCodeNip();
                    $nomPrenomArray = EtudiantRepository::getNomPrenomParCodeNip($code_nip);
                    $nomPrenom = $nomPrenomArray['Nom'] . ' ' . $nomPrenomArray['Prenom'];
                    $result = EtudiantRepository::recupererDetailsEtudiantParCodeNip($code_nip);

                    $etudiantInfo = $result['info'];
                    $etudiantDetailsPerSemester = $result['details'];

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => "Détail de $nomPrenom",
                        "informationsPersonelles" => $etudiantInfo,
                        "informationsParSemestre" => $etudiantDetailsPerSemester,
                        "code_nip" => $code_nip,
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estEtudiant()) { // Peut être amélioré à l'avenir pour permettre aux écoles aussi de se modifier
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiantExistant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);

        $mdp = $_GET['nvmdp'] ?? '';
        $mdp2 = $_GET['nvmdp2'] ?? '';

        if ($mdp !== $mdp2) {
            MessageFlash::ajouter("warning","Les mots de passe ne correspondent pas");
            self::afficherVue('vueGenerale.php', ["etudiant" => $etudiantExistant, "titre" => "Formulaire de mise à jour d'un etudiant", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);
            return;
        }
        $userexistant = (new UtilisateurRepository())->recupererParClePrimaire($_GET['login']);
        if($_GET["nvmdp"]==''){
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), $userexistant->getPasswordHash());
        }else {
            $user = new Utilisateur($userexistant->getLogin(), $userexistant->getType(), MotDePasse::hacher($_GET["nvmdp"]));
        }
        (new UtilisateurRepository)->mettreAJour($user);
        $etudiant = new Etudiant($user, $etudiantExistant->getCodeNip(), $etudiantExistant->getDemandes(), $etudiantExistant->getCodeUnique());
        (new EtudiantRepository)->mettreAJour($etudiant);

        if (ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("success", "L'étudiant a été mis à jour avec succès.");
            $etudiants = (new EtudiantRepository)->recuperer();
            $listeNomPrenom = array();
            foreach ($etudiants as $etudiant) {
                $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
                $listeNomPrenom[] = $nomPrenom;
            }
            self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"listeNomPrenom"=>$listeNomPrenom, "titre" => "Liste étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
        }else{
            MessageFlash::ajouter("success", "Votre compte a été mis à jour avec succès.");
            $etudiant=(new EtudiantRepository())->recupererParClePrimaire($_GET['login']);
            $nomPrenom=EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
            self::afficherVue('vueGenerale.php', [
                "user" => $etudiant,
                "nomPrenom"=>$nomPrenom,
                "titre" => "Compte Etudiant",
                "cheminCorpsVue" => "etudiant/compteEtudiant.php"
            ]);
        }
    }

    public static function afficherResultatRechercheEtudiant(): void
    {
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estProfesseur() && !ConnexionUtilisateur::estEcole()) {
            self::afficherErreurEtudiant("Vous n'avez pas de droit d'accès pour cette page");
            return;
        }
        $etudiants = EtudiantRepository::rechercherEtudiant($_GET['reponse']);
        $listeNomPrenom = array();
        foreach ($etudiants as $etudiant) {
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
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
            $nomPrenom = EtudiantRepository::getNomPrenomParCodeNip($etudiant->getCodeNip());
            $listeNomPrenom[] = $nomPrenom;
        }

        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants,"ecole" => $ecole, "listeNomPrenom" => $listeNomPrenom, "titre" => "Demande d'accès aux infos d'un étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }

    public static function getNoteForMatiere(int $idRessource, int $idEtudiant): float
    {
        $sql = "SELECT note FROM RELEASENote WHERE id_ressource = :id_ressource AND idEtudiant = :idEtudiant";
        $stmt = ConnexionBaseDeDonnees::getPdo()->prepare($sql);
        $stmt->execute(['id_ressource' => $idRessource, 'idEtudiant' => $idEtudiant]);

        $note = $stmt->fetchColumn();
        return $note !== false ? (float)$note : 0;
    }
    function genererAvisPdf($idEtudiant) {
        $etudiantRepository = new EtudiantRepository();

        $etudiant = $etudiantRepository->recupererParClePrimaire($idEtudiant);

        if (!$etudiant) {
            throw new Exception("Étudiant non trouvée");
        }

        $etudiantDetails = $etudiantRepository->recupererDetailsEtudiantParCodeNip($etudiant->getCodeNip());

        $content = "
        <h1>Fiche Avis Poursuite d’Études - Promotion 2023-2024</h1>
        <h2>Département Informatique IUT Montpellier-Sète</h2>
        <h3>FICHE D’INFORMATION ÉTUDIANT(E)</h3>
        <p><strong>NOM:</strong> {$etudiantDetails['info']['nom']}</p>
        <p><strong>Prénom:</strong> {$etudiantDetails['info']['prenom']}</p>
        <p><strong>Apprentissage en BUT 3:</strong> {$etudiant->getDemandes()}</p>
        <p><strong>Parcours BUT:</strong> {$etudiantDetails['details']['Parcours']}</p>
        <h3>Avis de l’équipe pédagogique pour la poursuite d’études après le BUT3</h3>
        <p><strong>En école d’ingénieur et master en informatique:</strong> {$etudiantDetails['details']['Avis_Ecole_dingénieur_et_master_en_info']}</p>
        <p><strong>En master en management:</strong> {$etudiantDetails['details']['Avis_Master_en_management']}</p>
        <h3>Nombre d’avis pour la promotion</h3>
        <p><strong>Très Favorable:</strong> 37</p>
        <p><strong>Favorable:</strong> 20</p>
        <p><strong>Réservé:</strong> 33</p>
        <p><strong>Master en management:</strong> 44</p>
        <p><strong>Favorable:</strong> 40</p>
        <p><strong>Réservé:</strong> 6</p>
        <p><strong>Signature du Responsable des Poursuites d’études par délégation du chef de département</strong></p>
    ";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("Avis_PE_2024_{$etudiantDetails}.pdf", ["Attachment" => false]);
    }
}
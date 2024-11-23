<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MessageFlash;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\HTTP\Cookie;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use PDO;
use Random\RandomException;
use TypeError;

class ControleurUtilisateur extends ControleurGenerique
{
    public static function afficherListe(): void
    {
        if (self::verifierAdminConnecte()) {
            $utilisateurs = (new UtilisateurRepository)->recupererOrdonneParType(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Liste des utilisateurs", "cheminCorpsVue" => 'utilisateur/liste.php']);  //"redirige" vers la vue
        }
    }

    public static function afficherListeUtilisateurOrdonneParLogin(): void
    {
        if (self::verifierAdminConnecte()) {
            $utilisateurs = UtilisateurRepository::recupererUtilisateurOrdonneParLogin(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Liste des utilisateurs", "cheminCorpsVue" => "utilisateur/liste.php"]);  //"redirige" vers la vue
        }
    }


    public static function afficherDetail(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            //self::afficherErreur("Veuillez vous connecter d'abord.");
            self::redirectionVersURL("warning", "Veuillez vous connecter d'abord", "afficherPreference&controleur=Connexion");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            if (!ConnexionUtilisateur::estProfesseur()) {
//                self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page.");
                self::redirectionVersURL("error", "Vous n'avez pas de droit d'accès pour cette page", "afficher&controleur=Accueil");
                return;
            }
        }
        try {

            $utilisateur = (new UtilisateurRepository)->recupererParClePrimaire($_GET['login']);

            if ($utilisateur == NULL) {
                MessageFlash::ajouter("warning", "L'utilisateur de login {$_GET['login']} n'existe pas");
                self::afficherErreurUtilisateur(" ");
            } else {
                if ($utilisateur->getType() == "etudiant") {
                    $etudiant = (new EtudiantRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    $idEtudiant = $etudiant->getIdEtudiant();
                    $nomPrenom = (new EtudiantRepository)->getNomPrenomParIdEtudiant($idEtudiant);

                    $titre = "Détail de l'étudiant {$nomPrenom['Prenom']} {$nomPrenom['Nom']}";
                    $result = EtudiantRepository::recupererTousLesDetailsEtudiantParId($idEtudiant);

                    $etudiantInfo = $result['info'];
                    $etudiantDetailsPerSemester = $result['details'];

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => $titre,
                        "nomPrenom" => $nomPrenom,
                        "informationsPersonelles" => $etudiantInfo,
                        "informationsParSemestre" => $etudiantDetailsPerSemester,
                        "idEtudiant" => $idEtudiant,
                        "codeUnique" => $etudiant->getCodeUnique(),
                        "loginEtudiant" => $etudiant->getUtilisateur()->getLogin(),
                        "cheminCorpsVue" => "etudiant/detailEtudiant.php"
                    ]);
                } else if ($utilisateur->getType() == "universite") {
                    if (!ControleurGenerique::verifierAdminConnecte()) return;
                    $ecole = (new EcoleRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de l'école {$ecole->getNom()} ", "cheminCorpsVue" => "ecole/detailEcole.php"]);
                } else if ($utilisateur->getType() == "professeur") {
                    if (!ControleurGenerique::verifierAdminConnecte()) return;
                    $professeur = (new ProfesseurRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Détail du professeur {$professeur->getNom()} ", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
                } else {
                    if (!ControleurGenerique::verifierAdminConnecte()) return;
                    self::afficherVue('vueGenerale.php', ['utilisateur' => $utilisateur, "titre" => "Détail utilisateur", "cheminCorpsVue" => "utilisateur/detail.php"]);
                }
            }
        } catch (TypeError $e) {
            MessageFlash::ajouter("warning", $e->getMessage());
            self::afficherErreurUtilisateur(" ");
        }
    }

    public static function afficherResultatRechercheEtudiant(): void
    {
        $avoirDroits = false;
        if (ConnexionUtilisateur::estAdministrateur()) $avoirDroits = true;
        if (ConnexionUtilisateur::estProfesseur()) $avoirDroits = true;
        if ($avoirDroits) {
            $etudiants = EtudiantRepository::rechercherEtudiantParLogin($_GET['reponse']);
            $listeNomPrenom = array();
            foreach ($etudiants as $etudiant) {
                $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
                $listeNomPrenom[] = $nomPrenom;
            }
            self::afficherVue("vueGenerale.php", ["etudiants" => $etudiants, "listeNomPrenom" => $listeNomPrenom, "titre" => "Résultat recherche étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
        }
    }

    public static function afficherResultatRechercheEcole(): void
    {
        if (self::verifierAdminConnecte()) {
            $ecoles = EcoleRepository::rechercherEcole($_GET['nom']);
            self::afficherVue("vueGenerale.php", ["ecoles" => $ecoles, "titre" => "Résultat recherche école", "cheminCorpsVue" => "ecole/listeEcole.php"]);
        }
    }

    public static function afficherResultatRechercheProfesseur(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeurs = ProfesseurRepository::rechercherProfesseur($_GET['reponse']);
            self::afficherVue("vueGenerale.php", ["professeurs" => $professeurs, "titre" => "Résultat recherche professeur", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
        }
    }

    public static function afficherResultatRechercheUtilisateur(): void
    {
        if (self::verifierAdminConnecte()) {
            $utilisateurs = UtilisateurRepository::rechercherUtilisateurParLogin($_GET["login"]);
            self::afficherVue("vueGenerale.php", ["utilisateurs" => $utilisateurs, "titre" => "Résultat recherche utilisateur", "cheminCorpsVue" => "utilisateur/liste.php"]);
        }
    }

    public static function afficherFormulaireCreationEcole(): void
    {
        if (self::verifierAdminConnecte()) {
            self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'ecole", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
        }
    }

    public static function creerEcoleDepuisFormulaire(): void
    {
        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            self::redirectionVersURL("warning", "Les mots de passes ne correspondent pas", "afficherFormulaireCreation&controleur=ecole");
//            self::afficherErreurUtilisateur("Mots de passe distincts");
            return;
        }

        ControleurEcole::creerDepuisFormulaire();
    }

    public static function afficherFormulaireCreationProfesseur(): void
    {
        if (self::verifierAdminConnecte()) {
            self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création du professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);
        }
    }

    public static function creerProfesseurDepuisFormulaire(): void
    {
        if (self::verifierAdminConnecte()) {
            $mdp = $_GET['mdp'] ?? '';
            $mdp2 = $_GET['mdp2'] ?? '';

            if ($mdp !== $mdp2) {
                MessageFlash::ajouter("warning", "Les mots de passes ne correspondent pas");
                self::afficherErreurUtilisateur(" ");
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
    }

    public static function afficherErreurUtilisateur(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, "utilisateur");
    }

    public static function supprimer(): void
    {
        if (self::verifierAdminConnecte()) {
            $login = $_GET["login"];
            (new UtilisateurRepository)->supprimer($login);
            MessageFlash::ajouter("success", "L'utilisateur de login " . htmlspecialchars($login) . " a bien été supprimé");
            $utilisateurs = (new UtilisateurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "login" => $login, "titre" => "Suppression d'utilisateur", "cheminCorpsVue" => "utilisateur/liste.php"]);
        }
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (self::verifierAdminConnecte()) {
            $utilisateur = (new UtilisateurRepository)->recupererParClePrimaire($_GET['login']);
            if ($utilisateur->getType() == "etudiant") {
                $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
                self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Formulaire de mise à jour d'etudiant", "cheminCorpsVue" => "etudiant/formulaireMiseAJourEtudiant.php"]);

            } else if ($utilisateur->getType() == "universite") {
                $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
                self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Formulaire de mise à jour d'ecole", "cheminCorpsVue" => "ecole/formulaireMiseAJourEcole.php"]);
            } else if ($utilisateur->getType() == "professeur") {
                $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
                self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Formulaire de mise à jour du professeur", "cheminCorpsVue" => "professeur/formulaireMiseAJourProfesseur.php"]);

            }
        }
    }

    /**
     * @throws RandomException
     */
    public static function mettreAJour(): void
    {
        if (self::verifierAdminConnecte()) {
            if ($_GET["type"] == "etudiant") {
                ControleurEtudiant::mettreAJour();
            } else if ($_GET["type"] == "universite") {
                ControleurEcole::mettreAJour();
            } else if ($_GET["type"] == "professeur") {
                ControleurProfesseur::mettreAJour();
            }
        }

    }

    public static function construireDepuisFormulaire(array $tableauDonneesFormulaire): Utilisateur
    {
        $mdpHache = MotDePasse::hacher($tableauDonneesFormulaire['mdp']);
        $utilisateur = new Utilisateur(
            $tableauDonneesFormulaire['login'],
            $tableauDonneesFormulaire['type'],
            $mdpHache
        );
        return $utilisateur;
    }

    public static function connecter(): void
    {
        $login = $_GET["login"];
        $mdpL = $_GET["password"];

        if (empty($login) || empty($mdpL)) {
            MessageFlash::ajouter("warning", "Login et/ou mot de passe manquant");
            self::afficherErreurUtilisateur("");
            return;
        }
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);

        if (empty($utilisateur)) {
            MessageFlash::ajouter("warning", "Login incorrect");
            self::afficherErreurUtilisateur(" ");
            return;
        }

        if (!MotDePasse::verifier($mdpL, $utilisateur->getPasswordHash())) {
            MessageFlash::ajouter("warning", "Mot de passe incorrect");
            self::afficherErreurUtilisateur(" ");
        }
        ConnexionUtilisateur::connecter($utilisateur->getLogin());

        if ($utilisateur->getType() == "etudiant") {
            MessageFlash::ajouter("success", "Etudiant connecté");
            $etudiant = (new EtudiantRepository)->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Etudiant connecté",
                "etudiant" => $etudiant,
                "cheminCorpsVue" => "etudiant/pageEtudiant.php"
            ]);
        } else if ($utilisateur->getType() == "universite") {
            $ecole = (new EcoleRepository())->recupererParClePrimaire($login);
            if ($ecole->isEstValide()) {
                MessageFlash::ajouter("success", "Ecole connecté");
                $futursEtudiants = (new EcoleRepository)::getFutursEtudiantsListe($login);
                self::afficherVue('vueGenerale.php', [
                    "ecole" => $ecole,
                    "futursEtudiants" => $futursEtudiants,
                    "titre" => "École connectée",
                    "cheminCorpsVue" => "ecole/pageEcole.php"
                ]);
            } else {
                ConnexionUtilisateur::deconnecter();
                self::afficherErreurUtilisateur("Ce compte n'a pas été validé par l'administrateur ");
            };
        } else if ($utilisateur->getType() == "professeur") {
            MessageFlash::ajouter("success", "Professeur connecté");
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Professeur connecté",
                "professeur" => $professeur,
                "cheminCorpsVue" => "professeur/detailProfesseur.php"
            ]);
        } else if ($utilisateur->getType() == "administrateur") {
            MessageFlash::ajouter("success", "Administrateur connecté");
            $administrateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Administrateur connecté",
                "administrateur" => $administrateur,
                "cheminCorpsVue" => "administrateur/administrateurConnecte.php"
            ]);
        }

    }



    // connexion ldap
    /*public static function connecterEtudiant(): void
    {
        $login = $_GET["login"];
        $mdpL = $_GET["password"];

        $url = "https://webinfo.iutmontp.univ-montp2.fr/~dainiuted/connection/connection_ldap.php";
        $data = http_build_query([
            "login" => $login,
            "mdpL" => $mdpL
        ]);
        $options = [
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => $data
            ]
        ];
        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        $responseData = json_decode($response, true);


        if ($responseData['status'] === 'error') {
            self::afficherErreurUtilisateur($responseData['message']);
            return;
        }

        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);

        if (empty($utilisateur)) {
            self::afficherErreurUtilisateur("Login incorrect");
            return;
        }
        ConnexionUtilisateur::connecter($utilisateur->getLogin());

        $etudiant = (new EtudiantRepository)->recupererParClePrimaire($login);
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "utilisateur" => $utilisateur,
            "titre" => "Etudiant connecté",
            "etudiant" => $etudiant,
            "cheminCorpsVue" => "etudiant/etudiantConnecte.php"
        ]);
    }*/


    public static function deconnecter(): void
    {
        ConnexionUtilisateur::deconnecter();
//        $utilisateurs = (new UtilisateurRepository())->recuperer();
        self::redirectionVersURL("success", "Déconnexion réussie", "home");
    }

    /**
     * @throws RandomException
     */
    /*pour importer les données de notre tables avec tous
    les informations pour sauvegarder seulement login, codeUnique et idEtudiant*/
    public static function refaire(): void
    {
        // histoire d'être sûr que c'est bien un admin qui fait ça :
        if (!self::verifierAdminConnecte()) {
            return;
        }

        $tables = ['semestre1_2024', 'semestre2_2024', 'semestre3_2024', 'semestre4_2024', 'semestre5_2024'];
        $pdo = ConnexionBaseDeDonnees::getPdo();

        foreach ($tables as $table) {
            $query = "
            SELECT LOWER(CONCAT(Nom, LEFT(Prénom, 1))) AS login, etudid AS etudid
            FROM {$table} AS s
            LEFT JOIN EtudiantTest AS e ON LOWER(CONCAT(s.Nom, LEFT(s.Prénom, 1))) = e.login
            WHERE e.login IS NULL
        ";

            $stmt = $pdo->query($query);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $insertStmt = $pdo->prepare("
            INSERT INTO EtudiantTest (login, codeUnique, idEtudiant)
            VALUES (:login, :codeUnique, :idEtudiant)
        ");

            foreach ($students as $student) {
                $etudiant = new Etudiant($student['login'], $student['etudid']);
                $codeUnique = $etudiant->getCodeUnique();

                $insertStmt->execute([
                    ':login' => $student['login'],
                    ':idEtudiant' => $student['etudid'],
                    ':codeUnique' => $codeUnique
                ]);
            }
        }
    }

    public static function setCookieBanner(): void
    {
        Cookie::enregistrer('bannerClosed', true, 10 * 365 * 24 * 60 * 60);
        header('Location: controleurFrontal.php?action=home');
        exit();
    }
}


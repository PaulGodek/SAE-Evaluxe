<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Lib\MotDePasse;
use App\GenerateurAvis\Modele\DataObject\Ecole;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\DataObject\Utilisateur;
use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use App\GenerateurAvis\Modele\Repository\UtilisateurRepository;
use Random\RandomException;
use TypeError;

class ControleurUtilisateur extends ControleurGenerique
{
    public static function afficherListe(): void
    {
        $utilisateurs = (new UtilisateurRepository)->recupererOrdonneParType(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Liste des utilisateurs", "cheminCorpsVue" => 'utilisateur/liste.php']);  //"redirige" vers la vue
    }


    public static function afficherListeUtilisateurOrdonneParLogin(): void
    {
        $utilisateurs = UtilisateurRepository::recupererUtilisateurOrdonneParLogin(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "titre" => "Liste des utilisateurs", "cheminCorpsVue" => "utilisateur/liste.php"]);  //"redirige" vers la vue
    }


    public static function afficherDetail(): void
    {
        try {

            $utilisateur = (new UtilisateurRepository)->recupererParClePrimaire($_GET['login']);

            if ($utilisateur == NULL) {
                self::afficherErreur("L'utilisateur de login {$_GET['login']} n'existe pas");
            } else {
                if ($utilisateur->getType() == "etudiant") {
                    $etudiant = (new EtudiantRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["etudiant" => $etudiant, "titre" => "Détail de l'étudiant {$etudiant->getPrenom()} {$etudiant->getNom()}", "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
                } else if ($utilisateur->getType() == "universite") {
                    $ecole = (new EcoleRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["ecole" => $ecole, "titre" => "Détail de l'école {$ecole->getNom()} ", "cheminCorpsVue" => "ecole/detailEcole.php"]);
                } else if ($utilisateur->getType() == "professeur") {
                    $professeur = (new ProfesseurRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Détail du professeur {$professeur->getNom()} ", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
                } else {
                    self::afficherVue('vueGenerale.php', ['utilisateur' => $utilisateur, "cheminCorpsVue" => "utilisateur/detail.php"]);
                }
            }
        } catch (TypeError $e) {
            self::afficherErreur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherResultatRechercheEtudiant(): void
    {

        $etudiants = EtudiantRepository::rechercherEtudiant($_GET['reponse']);
        self::afficherVue("vueGenerale.php", ["etudiants" => $etudiants, "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
    }

    public static function afficherResultatRechercheEcole(): void
    {
        $ecoles = EcoleRepository::rechercherEcole($_GET['nom']);
        self::afficherVue("vueGenerale.php", ["ecoles" => $ecoles, "cheminCorpsVue" => "ecole/listeEcole.php"]);
    }

    public static function afficherResultatRechercheProfesseur(): void
    {
        $professeurs = ProfesseurRepository::rechercherProfesseur($_GET['reponse']);
        self::afficherVue("vueGenerale.php", ["professeurs" => $professeurs, "cheminCorpsVue" => "professeur/listeProfesseur.php"]);
    }

    /**
     * @throws RandomException
     */
    /*
    public static function afficherResultatRecherche(): void
    {
        $utilisateur = (new UtilisateurRepository)->recupererParClePrimaire($_GET['login']);
        if ($utilisateur->getType() == "etudiant") {
            $etudiant = (new EtudiantRepository)->recupererParClePrimaire($_GET['login']);
            self::afficherVue("vueGenerale.php", ["etudiant" => $etudiant, "cheminCorpsVue" => "etudiant/detailEtudiant.php"]);
        } else if ($utilisateur->getType() == "universite") {
            $ecole = (new EcoleRepository)->recupererParClePrimaire($_GET['login']);
            self::afficherVue("vueGenerale.php", ["ecole" => $ecole, "cheminCorpsVue" => "ecole/detailEcole.php"]);
        } else if ($utilisateur->getType() == "professeur") {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            self::afficherVue("vueGenerale.php", ["professeur" => $professeur, "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
        }
    }*/
    // Toute la fonction est ultra bizarre, j'en réécris une qui correspond plus à ce qu'on veut

    public static function afficherResultatRechercheUtilisateur(): void
    {
        $utilisateurs = UtilisateurRepository::rechercherUtilisateurParLogin($_GET["login"]);
        self::afficherVue("vueGenerale.php", ["utilisateurs" => $utilisateurs, "cheminCorpsVue" => "utilisateur/liste.php"]);
    }

    public static function afficherFormulaireCreationEcole(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'ecole", "cheminCorpsVue" => "ecole/formulaireCreationEcole.php"]);
    }

    public static function creerEcoleDepuisFormulaire(): void
    {
        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            ControleurUtilisateur::afficherErreur("Mots de passe distincts");
            return;
        }

        ControleurEcole::creerDepuisFormulaire();
    }


    public static function afficherFormulaireCreationEtudiant(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création d'utilisateur", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    /**
     * @throws RandomException
     */
    public static function creerEtudiantDepuisFormulaire(): void
    {
        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            ControleurUtilisateur::afficherErreur("Mots de passe distincts");
            return;
        }
        $utilisateur = self::construireDepuisFormulaire($_GET);
        (new UtilisateurRepository)->ajouter($utilisateur);


        $etudiant = new Etudiant($_GET["login"], $_GET["nom"], $_GET["prenom"], $_GET["moyenne"]);
        (new EtudiantRepository)->ajouter($etudiant);
        $etudiants = (new EtudiantRepository())->recuperer();
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Création d'étudiant", "cheminCorpsVue" => "etudiant/etudiantCree.php"]);
    }

    public static function afficherFormulaireCreationProfesseur(): void
    {
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création du professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);
    }

    public static function creerProfesseurDepuisFormulaire(): void
    {
        $mdp = $_GET['mdp'] ?? '';
        $mdp2 = $_GET['mdp2'] ?? '';

        if ($mdp !== $mdp2) {
            ControleurUtilisateur::afficherErreur("Mots de passe distincts");
            return;
        }
        $utilisateur = self::construireDepuisFormulaire($_GET);
        (new UtilisateurRepository)->ajouter($utilisateur);


        $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
        (new ProfesseurRepository)->ajouter($professeur);
        $professeurs = (new ProfesseurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création du professeur", "cheminCorpsVue" => "professeur/professeurCree.php"]);
    }

    public static function afficherErreur(string $messageErreur = ""): void
    {
        self::afficherVue('vueGenerale.php', ["messageErreur" => $messageErreur, "titre" => "Erreur", "cheminCorpsVue" => "utilisateur/erreur.php"]);
    }

    public static function supprimer(): void
    {
        $login = $_GET["login"];
        (new UtilisateurRepository)->supprimer($login);
        $utilisateurs = (new UtilisateurRepository)->recuperer();
        self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "login" => $login, "titre" => "Suppression d'utilisateur", "cheminCorpsVue" => "utilisateur/utilisateurSupprime.php"]);
    }

    public static function afficherFormulaireMiseAJour(): void
    {
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

    /**
     * @throws RandomException
     */
    public static function mettreAJour(): void
    {
        //$utilisateur = new Utilisateur($_GET["login"], $_GET["type"], $_GET["password_hash"]);
        if ($_GET["type"] == "etudiant") {
            ControleurEtudiant::mettreAJour();
        } else if ($_GET["type"] == "universite") {
            ControleurEcole::mettreAJour();
        } else if ($_GET["type"] == "professeur") {
            ControleurProfesseur::mettreAJour();
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
            ControleurUtilisateur::afficherErreur("Login et/ou mot de passe manquant");
            return;
        }
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);

        if (empty($utilisateur)) {
            ControleurUtilisateur::afficherErreur("Login incorrect");
            return;
        }

        if (!MotDePasse::verifier($mdpL, $utilisateur->getPasswordHash())) {
            ControleurUtilisateur::afficherErreur("Mot de passe incorrect");
            return;
        }
        ConnexionUtilisateur::connecter($utilisateur->getLogin());


        if ($utilisateur->getType() == "universite") {
            $ecole = (new EcoleRepository())->recupererParClePrimaire($login);
            if ($ecole->isEstValide()) {
                ControleurUtilisateur::afficherVue('vueGenerale.php', [
                    "utilisateur" => $utilisateur,
                    "titre" => "Ecole connecté",
                    "ecole" => $ecole,
                    "cheminCorpsVue" => "ecole/ecoleConnecte.php"
                ]);
            } else {
                ConnexionUtilisateur::deconnecter();
                ControleurUtilisateur::afficherErreur("Ce compte n'a pas été validé par l'administrateur ");
            };
        } else if ($utilisateur->getType() == "professeur") {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Professeur connecté",
                "professeur" => $professeur,
                "cheminCorpsVue" => "professeur/professeurConnecte.php"
            ]);
        } else if ($utilisateur->getType() == "administrateur") {
            $administrateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Administrateur connecté",
                "administrateur" => $administrateur,
                "cheminCorpsVue" => "administrateur/administrateurConnecte.php"
            ]);
        }

    }

    public static function connecterEtudiant(): void
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
            ControleurUtilisateur::afficherErreur($responseData['message']);
            return;
        }

        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);

        if (empty($utilisateur)) {
            ControleurUtilisateur::afficherErreur("Login incorrect");
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
    }


    public static function deconnecter(): void
    {
        ConnexionUtilisateur::deconnecter();
        $utilisateurs = (new UtilisateurRepository())->recuperer();

        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "utilisateurs" => $utilisateurs,
            "titre" => "Utilisateur déconnecté",
            "cheminCorpsVue" => "utilisateur/utilisateurDeconnecte.php"
        ]);
    }

}


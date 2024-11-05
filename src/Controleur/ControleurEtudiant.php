<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;
use Random\RandomException;
use TypeError;

class ControleurEtudiant extends ControleurGenerique
{
    public static function afficherListe(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiants = (new EtudiantRepository)->recuperer(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    /**
     * @throws RandomException
     */
    public static function afficherListeEtudiantOrdonneParNom(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParNom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParPrenom(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParPrenom(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherListeEtudiantOrdonneParParcours(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        $etudiants = EtudiantRepository::recupererEtudiantsOrdonneParParcours(); //appel au modèle pour gérer la BD
        self::afficherVue('vueGenerale.php', ["etudiants" => $etudiants, "titre" => "Liste des etudiants", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);  //"redirige" vers la vue
    }

    public static function afficherDetail(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte() && !ControleurGenerique::verifierEtudiantConnecte()) return;
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
    }

    public static function afficherDetailEtudiantParCodeUnique(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte() && !ControleurGenerique::verifierEtudiantConnecte()) return;
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

    public static function afficherFormulaireCreation(): void
    {
        if (!ControleurGenerique::verifierAdminConnecte()) return;
        self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte étudiant", "cheminCorpsVue" => "etudiant/formulaireCreationEtudiant.php"]);
    }

    public static function afficherErreurEtudiant(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, "etudiant");
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
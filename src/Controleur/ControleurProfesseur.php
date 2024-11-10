<?php

namespace App\GenerateurAvis\Controleur;

use App\GenerateurAvis\Modele\DataObject\Professeur;
use App\GenerateurAvis\Modele\Repository\ProfesseurRepository;
use TypeError;

class ControleurProfesseur extends ControleurGenerique
{
    public static function afficherErreurProfesseur(string $messageErreur = ""): void
    {
        self::afficherErreur($messageErreur, 'professeur');
    }

    public static function afficherListe(): void
    {

        if (self::verifierAdminConnecte()) {
            $professeurs = (new ProfesseurRepository())->recuperer(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
        } 
    }

    public static function afficherListeProfesseurOrdonneParNom(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParNom(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
        } 
    }

    public static function afficherListeProfesseurOrdonneParPrenom(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeurs = ProfesseurRepository::recupererProfesseursOrdonneParPrenom(); //appel au modèle pour gérer la BD
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Liste des professeurs", "cheminCorpsVue" => "professeur/listeProfesseur.php"]);  //"redirige" vers la vue
        } 
    }

    public static function afficherDetail(): void
    {
        try {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            if ($professeur == NULL) {
                self::afficherErreurProfesseur("Le professeur {$_GET['login']} n'existe pas");
            } else {
                self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Détail de {$professeur->getNom()}", "cheminCorpsVue" => "professeur/detailProfesseur.php"]);
            }
        } catch (TypeError $e) {
            self::afficherErreurProfesseur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        if (self::verifierAdminConnecte()) {
            self::afficherVue('vueGenerale.php', ["titre" => "Formulaire de création de compte professeur", "cheminCorpsVue" => "professeur/formulaireCreationProfesseur.php"]);
        } 
    }

    public static function creerDepuisFormulaire(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeur = new professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
            (new ProfesseurRepository)->ajouter($professeur);
            $professeurs = (new ProfesseurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création de compte professeur", "cheminCorpsVue" => "professeur/professeurCree.php"]);
        } 
    }

    public static function supprimer(): void
    {
        if (self::verifierAdminConnecte()) {
            $login = $_GET["login"];
            (new ProfesseurRepository)->supprimer($login);
            $professeurs = (new ProfesseurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $login, "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurSupprime.php"]);
        } 
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeur = (new ProfesseurRepository)->recupererParClePrimaire($_GET['login']);
            self::afficherVue('vueGenerale.php', ["professeur" => $professeur, "titre" => "Formulaire de mise à jour de compte professeur", "cheminCorpsVue" => "professeur/formulaireMiseAJourProfesseur.php"]);
        } 
    }

    public static function mettreAJour(): void
    {
        if (self::verifierAdminConnecte()) {
            $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
            (new ProfesseurRepository)->mettreAJour($professeur);
            $professeurs = (new ProfesseurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "login" => $professeur->getLogin(), "titre" => "Suppression de compte professeur", "cheminCorpsVue" => "professeur/professeurMisAJour.php"]);
        } 
    }
}
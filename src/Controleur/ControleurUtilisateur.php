<?php

namespace App\GenerateurAvis\Controleur;

require __DIR__ . '/../../bootstrap.php';

use Exception;
use PDOException;
use Shuchkin\SimpleXLSX;
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
use JetBrains\PhpStorm\NoReturn;
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
            self::afficherErreur("Veuillez vous connecter d'abord.");
            return;
        }

        if (!ConnexionUtilisateur::estAdministrateur()) {
            if (!ConnexionUtilisateur::estProfesseur()) {
                self::afficherErreur("Vous n'avez pas de droit d'accès pour cette page.");
                return;
            }
        }
        try {

            $utilisateur = (new UtilisateurRepository)->recupererParClePrimaire($_GET['login']);

            if ($utilisateur == NULL) {
                self::afficherErreurUtilisateur("L'utilisateur de login {$_GET['login']} n'existe pas");
            } else {
                if ($utilisateur->getType() == "etudiant") {
                    $etudiant = (new EtudiantRepository)->recupererParClePrimaire($utilisateur->getLogin());
                    $nomPrenom = (new EtudiantRepository)->getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());

                    if ($nomPrenom) {
                        $titre = "Détail de l'étudiant {$nomPrenom['Prenom']} {$nomPrenom['Nom']}";
                    } else {
                        $titre = "Détail de l'étudiant (Nom et prénom non trouvés)";
                    }

                    self::afficherVue('vueGenerale.php', [
                        "etudiant" => $etudiant,
                        "titre" => $titre,
                        "cheminCorpsVue" => "etudiant/detailEtudiant.php",
                        "nomPrenom" => $nomPrenom
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
            self::afficherErreurUtilisateur("Jsp ce qu'il s'est passé dsl, voilà l'erreur : {$e->getMessage()}");
        }
    }

    public static function afficherResultatRechercheEtudiant(): void
    {
        $avoirDroits = false;
        if (ConnexionUtilisateur::estAdministrateur()) $avoirDroits = true;
        if (ConnexionUtilisateur::estProfesseur()) $avoirDroits = true;
        if ($avoirDroits) {
            $etudiants = EtudiantRepository::rechercherEtudiantParLogin($_GET['reponse']);
            self::afficherVue("vueGenerale.php", ["etudiants" => $etudiants, "titre" => "Résultat recherche étudiant", "cheminCorpsVue" => "etudiant/listeEtudiant.php"]);
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
            self::afficherErreurUtilisateur("Mots de passe distincts");
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
                self::afficherErreurUtilisateur("Mots de passe distincts");
                return;
            }
            $utilisateur = self::construireDepuisFormulaire($_GET);
            (new UtilisateurRepository)->ajouter($utilisateur);


            $professeur = new Professeur($_GET["login"], $_GET["nom"], $_GET["prenom"]);
            (new ProfesseurRepository)->ajouter($professeur);
            $professeurs = (new ProfesseurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["professeurs" => $professeurs, "titre" => "Création du professeur", "cheminCorpsVue" => "professeur/professeurCree.php"]);
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
            $utilisateurs = (new UtilisateurRepository)->recuperer();
            self::afficherVue('vueGenerale.php', ["utilisateurs" => $utilisateurs, "login" => $login, "titre" => "Suppression d'utilisateur", "cheminCorpsVue" => "utilisateur/utilisateurSupprime.php"]);
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
            self::afficherErreurUtilisateur("Login et/ou mot de passe manquant");
            return;
        }
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);

        if (empty($utilisateur)) {
            self::afficherErreurUtilisateur("Login incorrect");
            return;
        }

        if (!MotDePasse::verifier($mdpL, $utilisateur->getPasswordHash())) {
            self::afficherErreurUtilisateur("Mot de passe incorrect");
            return;
        }
        ConnexionUtilisateur::connecter($utilisateur->getLogin());

        if ($utilisateur->getType() == "etudiant") {
            MessageFlash::ajouter("success", "Etudiant connecté");
            $etudiant = (new EtudiantRepository)->recupererParClePrimaire($login);
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $utilisateur,
                "titre" => "Etudiant connecté",
                "etudiant" => $etudiant,
                "cheminCorpsVue" => "etudiant/etudiantConnecte.php"
            ]);
        } else if ($utilisateur->getType() == "universite") {
            $ecole = (new EcoleRepository())->recupererParClePrimaire($login);
            if ($ecole->isEstValide()) {
                MessageFlash::ajouter("success", "Ecole connecté");
                ControleurUtilisateur::afficherVue('vueGenerale.php', [
                    "utilisateur" => $utilisateur,
                    "titre" => "Ecole connecté",
                    "ecole" => $ecole,
                    "cheminCorpsVue" => "ecole/ecoleConnecte.php"
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
                "cheminCorpsVue" => "professeur/professeurConnecte.php"
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

    public static function afficherFormulaireImport(): void
    {
        $cheminCorpsVue = 'utilisateur/importForm.php';
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "titre" => "Importer fichiers",
            "cheminCorpsVue" => $cheminCorpsVue]);
    }

    /**
     * @throws Exception
     */
    public static function importerExcel(): void
    {
        if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Échec du téléchargement du fichier Excel.");
        }

        $filePath = $_FILES['excelFile']['tmp_name'];
        $fileName = pathinfo($_FILES['excelFile']['name'], PATHINFO_FILENAME);
        if (preg_match('/semestre[-_](\d{1,2})[-_](\d{4})/', $fileName, $matches)) {
            $semesterYear = 'semestre' . $matches[1] . '_' . $matches[2];
        } else {
            $semesterYear = $fileName;
        }

        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '_', $semesterYear);

        $sheetData = self::parseExcelFile($filePath);

        if (empty($sheetData)) {
            throw new Exception("Le fichier Excel est vide.");
        }

        $columns = self::extractColumns(array_shift($sheetData));

        self::createDatabaseTable($tableName, $columns);

        self::insertDataIntoTable($tableName, $columns, $sheetData);

        MessageFlash::ajouter('success', "Fichier Excel importé avec succès dans un tableau `$tableName`.");
        echo '<script type="text/javascript">window.location.href = "controleurFrontal.php?controleur=utilisateur&action=afficherListe";</script>';
        exit;
    }


    /**
     * @throws Exception
     */
    private static function parseExcelFile(string $filePath): array
    {
        if ($xlsx = SimpleXLSX::parse($filePath)) {
            return $xlsx->rows();
        }
        throw new Exception("Échec de l'analyse du fichier Excel. Erreur : " . SimpleXLSX::parseError());
    }

    private static function extractColumns(array $row): array
    {
        $columns = [];
        $usedColumns = [];

        $nom = 0;
        foreach ($row as $index => $col) {
            $col = trim($col);
            if (in_array($col, $usedColumns)) {
                $counter = 1;
                $originalCol = $col;
                while (in_array($col, $usedColumns)) {
                    $col = $originalCol . "_" . $counter;
                    $counter++;
                }
            }

            $usedColumns[] = $col;

            $columns[] = $col ?: "column_$index";
        }
        return $columns;
    }

    private static function createDatabaseTable(string $tableName, array $columns): void
    {
        $columnDefinitions = [];
        foreach ($columns as $index => $col) {
            if ($index === 0) {
                $columnDefinitions[] = "`$col` INT PRIMARY KEY";
            } else {
                $columnDefinitions[] = "`$col` TEXT";
            }
        }

        $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(',', $columnDefinitions) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC";

        $pdo = ConnexionBaseDeDonnees::getPdo();
        $pdo->exec($createTableQuery);
    }

    /**
     * @throws Exception
     */
    private static function insertDataIntoTable(string $tableName, array $columns, array $data): void
    {
        $sanitizedColumns = array_map(fn($col) => "`$col`", $columns);

        $insertQuery = "INSERT INTO `$tableName` (" . implode(',', $sanitizedColumns) . ") VALUES (" . str_repeat('?,', count($columns) - 1) . "?)";
        $pdo = ConnexionBaseDeDonnees::getPdo();
        $stmt = $pdo->prepare($insertQuery);

        foreach ($data as $rowIndex => $row) {
            $row = array_slice($row, 0, count($columns));
            $etudidIndex = array_search('etudid', $columns);
            if ($etudidIndex !== false && empty($row[$etudidIndex])) {
                return;
            }

            $row = array_map(fn($value) => is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'auto') : $value, $row);

            try {
                $stmt->execute($row);
            } catch (PDOException $e) {
                throw new Exception("Erreur d'insertion d'une ligne #$rowIndex: " . $e->getMessage());
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


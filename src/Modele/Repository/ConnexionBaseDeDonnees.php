<?php
namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Configuration\ConfigurationBaseDeDonnees;
use PDO;

class ConnexionBaseDeDonnees {
    public static ?ConnexionBaseDeDonnees $instance = null;
    private PDO $pdo;
    private function __construct() {
        $port = ConfigurationBaseDeDonnees::getPort();
        $nomBaseDeDonnees = ConfigurationBaseDeDonnees::getNomBaseDeDonnees();
        $login = ConfigurationBaseDeDonnees::getLogin();
        $motDePasse = ConfigurationBaseDeDonnees::getMotDePasse();
        $nomHote = ConfigurationBaseDeDonnees::getNomHote();
        $this->pdo = new PDO("mysql:host=$nomHote;port=$port;dbname=$nomBaseDeDonnees" ,$login, $motDePasse, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getPdo(): PDO {
        return ConnexionBaseDeDonnees::getInstance()->pdo;
    }

    // getInstance s'assure que le constructeur ne sera
    // appelé qu'une seule fois.
    // L'unique instance crée est stockée dans l'attribut $instance
    private static function getInstance() : ConnexionBaseDeDonnees {
        // L'attribut statique $instance s'obtient avec la syntaxe ConnexionBaseDeDonnees::$instance
        if (is_null(ConnexionBaseDeDonnees::$instance))
            // Appel du constructeur
            ConnexionBaseDeDonnees::$instance = new ConnexionBaseDeDonnees();
        return ConnexionBaseDeDonnees::$instance;
    }
}
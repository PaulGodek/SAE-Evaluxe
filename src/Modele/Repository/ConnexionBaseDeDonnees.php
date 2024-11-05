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

    private static function getInstance() : ConnexionBaseDeDonnees {
        if (is_null(ConnexionBaseDeDonnees::$instance))
            ConnexionBaseDeDonnees::$instance = new ConnexionBaseDeDonnees();
        return ConnexionBaseDeDonnees::$instance;
    }
}
<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;

$pdo = ConnexionBaseDeDonnees::getPdo();

$tables = ['semestre1_2024', 'semestre2_2024', 'semestre3_2024', 'semestre4_2024', 'semestre5_2024'];
$idEtudiant = $etudiant->getIdEtudiant();
$etudiantDetails = null;

foreach ($tables as $table) {
    $query = "SELECT Nom, Prénom, Abs, Just_1, Moy FROM {$table} WHERE etudid = :idEtudiant";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idEtudiant' => $idEtudiant]);

    if ($etudiantDetails = $stmt->fetch(PDO::FETCH_ASSOC)) {
        break;
    }
}

if ($etudiantDetails) {
    $nom = htmlspecialchars($etudiantDetails['Nom']);
    $prenom = htmlspecialchars($etudiantDetails['Prénom']);
    $abs = htmlspecialchars($etudiantDetails['Abs']);
    $just1 = htmlspecialchars($etudiantDetails['Just_1']);
    $moy = htmlspecialchars($etudiantDetails['Moy']);

    echo "Détails de l'étudiant:<br>";
    echo "Nom: {$nom}<br>";
    echo "Prénom: {$prenom}<br>";
    echo "Absences: {$abs}<br>";
    echo "Justifications: {$just1}<br>";
    echo "Moyenne: {$moy}<br>";
} else {
    echo "L'étudiant avec ID {$idEtudiant} n'a pas été trouvé dans les tables.";
}


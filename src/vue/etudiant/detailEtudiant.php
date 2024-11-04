<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\ConnexionBaseDeDonnees;

$pdo = ConnexionBaseDeDonnees::getPdo();

$tables = ['semestre1_2024', 'semestre2_2024', 'semestre3_2024', 'semestre4_2024', 'semestre5_2024'];
$idEtudiant = $etudiant->getIdEtudiant();
$etudiantDetailsPerSemester = [];
$studentInfo = null;

foreach ($tables as $table) {
    $query = "SELECT Nom, Prénom, Abs, Just_1, Moy FROM {$table} WHERE etudid = :idEtudiant";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':idEtudiant' => $idEtudiant]);

    if ($details = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!$studentInfo) {
            $studentInfo = [
                'nom' => htmlspecialchars($details['Nom']),
                'prenom' => htmlspecialchars($details['Prénom']),
            ];
        }

        $etudiantDetailsPerSemester[] = [
            'semester' => $table,
            'abs' => htmlspecialchars($details['Abs']),
            'just1' => htmlspecialchars($details['Just_1']),
            'moy' => htmlspecialchars($details['Moy']),
        ];
    }
}

if ($studentInfo) {
    echo "<strong>Détails de l'étudiant:</strong> {$studentInfo['nom']} {$studentInfo['prenom']}<br><br>";

    foreach ($etudiantDetailsPerSemester as $details) {
        echo "<strong>Semestre:</strong> {$details['semester']}<br>";
        echo "Absences: {$details['abs']}<br>";
        echo "Justifications: {$details['just1']}<br>";
        echo "Moyenne: {$details['moy']}<br><br>";
    }
} else {
    echo "Aucun détail n'a été trouvé pour l'étudiant avec ID {$idEtudiant}.";
}

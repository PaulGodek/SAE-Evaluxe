<?php
/** @var Etudiant $etudiant */

use App\GenerateurAvis\Lib\ConnexionUtilisateur;
use App\GenerateurAvis\Modele\DataObject\Etudiant;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;

$idEtudiant = $etudiant->getIdEtudiant();
$estEcole = ConnexionUtilisateur::estEcole();
$estEtudiant = ConnexionUtilisateur::estEtudiant();
if($estEcole){
    $type = "universite";
} else {
    $type = "autre";
}

switch ($type) {
    case "universite":
        $result = EtudiantRepository::recupererDetailsEtudiantParId($idEtudiant);

        $etudiantInfo = $result['info'];
        $etudiantDetailsPerSemester = $result['details'];

        if ($etudiantInfo) {
            echo '<div class="etudiant-details">';
            echo "<h2>Détails de l'étudiant</h2>";
            echo "<p>Nom: {$etudiantInfo['nom']}</p>";
            echo "<p>Prénom: {$etudiantInfo['prenom']}</p>";

            foreach ($etudiantDetailsPerSemester as $table => $details) {
                preg_match('/semestre(\d+)_\d+/', $table, $matches);
                $semesterNumber = $matches[1];
                echo '<div class="semester-details">';
                echo "<h3>Semestre: {$semesterNumber} </h3>";
                echo "<p>Absences non justifiées: " . max(0, $details['abs'] - $details['just1']) . "</p>";
                echo "<p>Moyenne: {$details['moyenne']}</p>";
                if($details['parcours']!=='-'){
                    echo "<p>Parcours: {$details['parcours']}</p>";
                }
                foreach ($details['ue_details'] as $ueDetail) {

                    if($ueDetail['moy'] !== 'N/A' ){
                        echo '<div class="ue-detail">';
                        echo "<h4>{$ueDetail['ue']}</h4>";
                        echo "<p>Moyenne: " . ($ueDetail['moy']) . "</p>";
                        echo '</div>';
                    }
                }

                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
        }
        break;
    default:
        $result = EtudiantRepository::recupererTousLesDetailsEtudiantParId($idEtudiant);

        $etudiantInfo = $result['info'];
        $etudiantDetailsPerSemester = $result['details'];
        if($estEtudiant){
            $codeUnique = EtudiantRepository::getCodeUniqueEtudiantConnecte();
        }

        if ($etudiantInfo) {
            echo '<div class="etudiant-details">';
            echo "<h2>Détails de l'étudiant</h2>";
            if($estEtudiant){
                echo "<h3>Code unique: {$codeUnique}</h3>";
            }
            echo "<p>Nom: {$etudiantInfo['nom']}</p>";
            echo "<p>Prénom: {$etudiantInfo['prenom']}</p>";
            echo "<p>Id étudiant: {$etudiantInfo['etudid']}</p>";
            echo "<p>Code nip: {$etudiantInfo['codenip']}</p>";
            echo "<p>Civilité: {$etudiantInfo['civ']}</p>";
            if($etudiantInfo['bac']!=='N/A'){
                echo "<p>Bac: {$etudiantInfo['bac']}</p>";
            }
            if($etudiantInfo['specialite']!=='N/A'){
                echo "<p>Spécialité: {$etudiantInfo['specialite']}</p>";
            }
            if($etudiantInfo['typeAdm']!=='N/A'){
                echo "<p>Type d'admission: {$etudiantInfo['typeAdm']}</p>";
            }
            if($etudiantInfo['rgAdm']!=='N/A'){
                echo "<p>Rg. Adm.: {$etudiantInfo['rgAdm']}</p>";
            }

            foreach ($etudiantDetailsPerSemester as $table => $details) {
                preg_match('/semestre(\d+)_\d+/', $table, $matches);
                $semesterNumber = $matches[1] ?? 'Inconnu';
                echo '<div class="semester-details">';
                echo "<h3>Semestre: {$semesterNumber}</h3>";

                foreach ($details as $column => $value) {
                    if ($column == 'abs' && isset($details['just1'])) {
                        echo "<p>Absences non justifiées: " . max(0, $value - $details['just1']) . "</p>";
                    } elseif ($column == 'just1') {
                        continue;
                    } elseif ($value !== '') {
                        echo "<p>" . ucfirst(str_replace('_', ' ', $column)) . ": $value</p>";
                    }
                }
                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>Aucun détail n\'a été trouvé pour l\'étudiant avec ID ' . htmlspecialchars($idEtudiant) . '.</p>';
        }
        break;
}
?>

<!-- CSS embedded within the PHP file to apply to only this page -->
<style>
    /* Page Specific Styles */
    .etudiant-details {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .etudiant-details h2 {
        text-align: center;
        color: #2c3e50;
    }

    .semester-details {
        background-color: #f4f4f9;
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
    }

    .semester-details h3 {
        color: #2980b9;
    }

    .ue-detail {
        margin-top: 10px;
    }

    .ue-detail h4 {
        color: #3498db;
    }

    .ue-detail p {
        color: #555;
    }

    .etudiant-details p {
        font-size: 16px;
        line-height: 1.5;
    }

    .etudiant-details p strong {
        color: #2980b9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #3498db;
        color: white;
    }

    td {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>


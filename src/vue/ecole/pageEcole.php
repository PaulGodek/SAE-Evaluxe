<?php

namespace App\GenerateurAvis\vue\ecole;

use App\GenerateurAvis\Modele\Repository\EcoleRepository;
use App\GenerateurAvis\Modele\Repository\EtudiantRepository;

$loginEcole = $_SESSION['loginEcole'] ?? null;
$ecole = EcoleRepository::recupererEcoleParLogin($loginEcole);
$etudiants = EtudiantRepository::recupererEtudiants();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_POST['codeUnique'])) {
    $codeUnique = $_GET['codeUnique'];

    $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($codeUnique);

    if ($etudiant) {
        $ecole->addFuturEtudiant($codeUnique);
        $message = "Étudiant avec code unique '$codeUnique' ajouté avec succès.";
    } else {
        $message = "Étudiant avec code unique '$codeUnique' n'existe pas.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Écoles</title>
</head>
<body>
<h1>Gestion de l'École: <?php echo $ecole->getNom(); ?></h1>

<?php include 'formulaireTrouverEtudiantAvecCodeUnique.php'; ?>

<h2>Étudiants Associés</h2>
<ul>
    <?php foreach ($ecole->getFutursEtudiants() as $code): ?>
        <li><?php echo $code; ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>

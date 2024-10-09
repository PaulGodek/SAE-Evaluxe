<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Écoles</title>
</head>
<body>
<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;

/** @var Ecole $ecole */
?>
<h1>Gestion de l'École: <?php echo htmlspecialchars($ecole->getNom()); ?></h1>

<h2>Ajouter un Étudiant</h2>
<form method="GET" action="controleurFrontal.php">
    <input type="hidden" name="action" value="ajouterEtudiant"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <input type="hidden" name="login" value="<?php echo htmlspecialchars($ecole->getLogin()); ?>"/>
    <label for="codeUnique">Code Unique de l'Étudiant:</label>
    <input type="text" id="codeUnique" name="codeUnique" required>
    <button type="submit">Ajouter Étudiant</button>
</form>

<h2>Étudiants Associés</h2>
<ul>
    <?php foreach ($ecole->getFutursEtudiants() as $code): ?>
        <li><?php echo htmlspecialchars($code); ?></li>
    <?php endforeach; ?>
</ul>

<?php if (isset($message)) echo "<p>$message</p>"; ?>
</body>
</html>

<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;
use \App\GenerateurAvis\Modele\Repository\EtudiantRepository;

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
    <button class="button-submit" type="submit">Ajouter Étudiant</button>
</form>

<h2>Étudiants Associés</h2>
<ul>
    <?php foreach ($ecole->getFutursEtudiants() as $code): ?>
        <?php $etudiant = EtudiantRepository::recupererEtudiantParCodeUnique($code);
        $nomPrenom = EtudiantRepository::getNomPrenomParIdEtudiant($etudiant->getIdEtudiant());
        if ($nomPrenom) {
            $nomHTML = htmlspecialchars($nomPrenom['Nom']);
            $prenomHTML = htmlspecialchars($nomPrenom['Prenom']);
        } else {
            $nomHTML = $prenomHTML = 'Nom inconnu';
        } ?>
        <li>
            <a href='controleurFrontal.php?controleur=etudiant&action=afficherDetailEtudiantParCodeUnique&codeUnique=<?php echo urlencode($code); ?>'>
                <?php echo htmlspecialchars($nomHTML) . " " . htmlspecialchars($prenomHTML) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

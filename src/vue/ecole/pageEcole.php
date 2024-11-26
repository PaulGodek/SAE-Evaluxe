<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;

/** @var Ecole $ecole */
/** @var array<array{codeUnique: string, nom: string, prenom: string}> $futursEtudiants */

?>
<h1>Gestion de l'École: <?php echo htmlspecialchars($ecole->getNom()); ?></h1>

<h2>Ajouter un Étudiant</h2>
<form method="GET" action="controleurFrontal.php">
    <input type="hidden" name="action" value="ajouterEtudiant"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <input type="hidden" name="login" value="<?php echo htmlspecialchars($ecole->getUtilisateur()->getLogin()); ?>"/>
    <p class="InputAddOn">
        <label class="InputAddOn-item" for="codeUnique_id">Code Unique de l'Étudiant:&#42;</label>
        <input class="InputAddOn-field" type="text" name="codeUnique" id="codeUnique_id" required>
    </p>
    <!--    <label for="codeUnique">Code Unique de l'Étudiant:</label>-->
    <!--    <input type="text" id="codeUnique" name="codeUnique" required>-->
    <button class="button-submit" type="submit">Ajouter Étudiant</button>
</form>

<h2>Étudiants Associés</h2>
<ul>
    <?php foreach ($futursEtudiants as $etudiant): ?>
        <li>
            <a href='controleurFrontal.php?controleur=etudiant&action=afficherDetailEtudiantParCodeUnique&codeUnique=<?php echo urlencode($etudiant['codeUnique']); ?>'>
                <?php echo htmlspecialchars($etudiant['nom']) . " " . htmlspecialchars($etudiant['prenom']); ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<section>
    <h1>Liste des agrégations</h1>

    <?php if (empty($agre)): ?>
        <p>Aucune agrégation n'a été trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
<!--                <th>ID</th>-->
                <th>Nom</th>
<!--                <th>Parcours</th>-->
<!--                <th>Login</th>-->
                <th>Actions</th>
                <th>Details</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($agre as $agregation): ?>
                <tr>
<!--                    <td>--><?php //= htmlspecialchars($agregation->getId()) ?><!--</td>-->
                    <td><?= htmlspecialchars($agregation->getNom()) ?></td>
<!--                    <td>--><?php //= htmlspecialchars($agregation->getParcours()) ?><!--</td>-->
<!--                    <td>--><?php //= htmlspecialchars($agregation->getLogin()) ?><!--</td>-->
                    <td>
<!--                        <a href="controleurFrontal.php?controleur=agregation&action=afficherFormulaireMiseAJour&id=--><?php //= urlencode($agregation->getId()) ?><!--">Modifier</a>-->
                        <a href="controleurFrontal.php?controleur=agregation&action=supprimerAgregation&id=<?= urlencode($agregation->getId()) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette agrégation ?');">Supprimer</a>
                    </td>
                    <td>
                        <a href="controleurFrontal.php?controleur=agregation&action=afficherDetailAgregation&id=<?= urlencode($agregation->getId()) ?>">Détails</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="controleurFrontal.php?action=afficherCreerAgregation&controleur=agregation" class="button">Ajouter une nouvelle agrégation</a>
    </p>
</section>

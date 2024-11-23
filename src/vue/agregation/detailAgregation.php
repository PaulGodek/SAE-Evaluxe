<section>
    <h1>Détails de l'Agrégation</h1>

    <?php if (!empty($agregationDetails['nom_agregation'])): ?>
        <p><strong>Nom:</strong> <?= htmlspecialchars($agregationDetails['nom_agregation']) ?></p>
    <?php endif; ?>

    <?php if (!empty($agregationDetails['parcours'])): ?>
        <p><strong>Parcours:</strong> <?= htmlspecialchars($agregationDetails['parcours']) ?></p>
    <?php endif; ?>

    <?php if (!empty($agregationDetails['login'])): ?>
        <p><strong>Login:</strong> <?= htmlspecialchars($agregationDetails['login']) ?></p>
    <?php endif; ?>

    <h2>Matières</h2>
    <?php if (empty($agregationDetails['matieres'])): ?>
        <p>Aucune matière trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Id Ressource</th>
                <th>Matière</th>
                <th>Coefficient</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($agregationDetails['matieres'] as $matiere): ?>
                <tr>
                    <td><?= htmlspecialchars($matiere['id_ressource']) ?></td>
                    <td><?= htmlspecialchars($matiere['matiere']) ?></td>
                    <td><?= htmlspecialchars($matiere['coefficient']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

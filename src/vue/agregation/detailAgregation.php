<section>
    <h1>Détails de l'Agrégation </h1>

    <?php if (!empty($agregationDetails['nom_agregation'])): ?>
        <p><strong>Nom:</strong> <?= htmlspecialchars($agregationDetails['nom_agregation']) ?></p>
    <?php endif; ?>

    <?php if (!empty($agregationDetails['parcours'])): ?>
        <p><strong>Parcours:</strong> <?= htmlspecialchars($agregationDetails['parcours']) ?></p>
    <?php endif; ?>

    <?php if (!empty($agregationDetails['login'])): ?>
        <p><strong>Login:</strong> <?= htmlspecialchars($agregationDetails['login']) ?></p>
    <?php endif; ?>

    <?php if (empty($agregationDetails['matieres'])): ?>
        <p>Aucune matière trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Id Ressource</th>
                <th>Nom Ressource</th>
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

<style>
    section h1 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 20px;
    }

    section p {
        font-size: 1.1rem;
        color: #555;
        margin: 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 8px 12px;
        text-align: center;
        font-size: 1rem;
    }

    table th {
        background-color: #f8f8f8;
        color: #333;
    }

    table td {
        background-color: #fafafa;
    }

    table tr:nth-child(even) td {
        background-color: #f1f1f1;
    }

    table tr:hover td {
        background-color: #e2e2e2;
    }

    section p {
        font-size: 1rem;
        text-align: center;
        margin-top: 20px;
    }

    section h2 {
        font-size: 1.5rem;
        color: #444;
        margin-top: 30px;
        border-bottom: 2px solid #ccc;
        padding-bottom: 10px;
    }

</style>
<section>
    <h1>Liste des agrégations</h1>

    <?php if (empty($agre)): ?>
        <p>Aucune agrégation n'a été trouvée.</p>
    <?php else: ?>
        <?php /** @var bool $admin */
        if ($admin) {
            echo "<p>Les agrégations en gras sont celles sur lesquelles sont basé le calcul des avis</p>";
        }
        ?>

        <table>
            <thead>
            <tr>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($agre as $agregation): ?>
                <tr>
                    <td><?php
                        if ($agregation->getId() == 1 || $agregation->getId() == 2) {
                            echo "<b>" . htmlspecialchars($agregation->getNom()) . "</b>";
                        } else {
                            echo htmlspecialchars($agregation->getNom());
                        }?></td>
                    <td>
                        <a href="controleurFrontal.php?controleur=agregation&action=supprimerAgregation&id=<?= urlencode($agregation->getId()) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette agrégation ?');">Supprimer</a> |
                        <a href="controleurFrontal.php?controleur=agregation&action=afficherDetailAgregation&id=<?= urlencode($agregation->getId()) ?>">Détails</a>
                        <a href="controleurFrontal.php?controleur=agregation&action=afficherFormulaireMiseAJour&id=<?= urlencode($agregation->getId()) ?>">Modifier</a>

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


<style>
    section {
        padding: 20px;
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 800px;
    }

    section h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
    }

    table thead th {
        font-weight: bold;
        padding: 10px;
        text-align: left;
        border-bottom: 2px solid #ddd;
    }

    table tbody td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table tbody a {
        color: var(--rougeUM);
        text-decoration: none;
        font-weight: bold;
    }

    table tbody a:hover {
        text-decoration: underline;
    }

</style>
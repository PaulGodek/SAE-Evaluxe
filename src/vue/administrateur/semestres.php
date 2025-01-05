<?php
if (!empty($semestres)) : ?>
    <h2>Liste des semestres</h2>
    <table class="semestres-table">
        <thead>
        <tr>
            <th>Nom du semestre :</th>
            <th>Publié :</th>
            <th>Actions :</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($semestres as $semestre): ?>
            <tr>
                <td><?php echo htmlspecialchars($semestre['nomTable']); ?></td>
                <td>
                    <?php echo $semestre['estPublie'] ? 'Oui' : 'Non'; ?>
                </td>
                <td>
                    <?php if (!$semestre['estPublie']): ?>
                        <!-- Publish button -->
                        <form method="POST"
                              action="controleurFrontal.php?action=publierSemestre&controleur=administrateur">
                            <input type="hidden" name="nomSemestre"
                                   value="<?php echo htmlspecialchars($semestre['nomTable']); ?>">
                            <button type="submit" class="btn-publish">Publier</button>
                        </form>
                    <?php endif; ?>

                    <!-- Delete button -->
                    <form method="POST"
                          action="controleurFrontal.php?action=supprimerSemestre&controleur=administrateur"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce semestre ?');">
                        <input type="hidden" name="nomSemestre"
                               value="<?php echo htmlspecialchars($semestre['nomTable']); ?>">
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Aucun semestre disponible.</p>
<?php endif; ?>

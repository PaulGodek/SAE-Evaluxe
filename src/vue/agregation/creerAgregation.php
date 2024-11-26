<section>
    <h1>Créer une agrégation</h1>
    <form method="get" action="controleurFrontal.php">
        <input type="hidden" name="action" value="creerAgregationDepuisFormulaire"/>
        <input type="hidden" name="controleur" value="agregation"/>

        <label for="nom">Nom de l'agrégation:</label>
        <input type="text" id="nom" name="nom" required>

        <h2>Sélectionnez les matières et leurs coefficients:</h2>
        <div id="matieres">
            <div class="matiere">
                <label>Matière:</label>
                <select name="matieres[]" required>
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($ressources as $ressource): ?>
                        <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                            <?= htmlspecialchars($ressource->getId_ressource()) ?>: <?= htmlspecialchars($ressource->getNom()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Coefficient:</label>
                <input type="number" name="coefficients[]" placeholder="Coefficient" required>
                <button type="button" class="supprimerMatiere">Supprimer</button>
            </div>
        </div>

        <button type="button" id="ajouterMatiere">Ajouter une matière</button>
        <br><br>
        <button type="submit">Créer l'agrégation</button>
    </form>
</section>

<script>
    document.getElementById('ajouterMatiere').addEventListener('click', function () {
        const matiereDiv = document.createElement('div');
        matiereDiv.className = 'matiere';
        matiereDiv.innerHTML = `
            <label>Matière:</label>
            <select name="matieres[]" required>
                <option value="">-- Sélectionnez une matière --</option>
                <?php foreach ($ressources as $ressource): ?>
                    <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                        <?= htmlspecialchars($ressource->getId_ressource()) ?>: <?= htmlspecialchars($ressource->getNom()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Coefficient:</label>
            <input type="number" name="coefficients[]" placeholder="Coefficient" required>
            <button type="button" class="supprimerMatiere">Supprimer</button>
        `;
        document.getElementById('matieres').appendChild(matiereDiv);

        matiereDiv.querySelector('.supprimerMatiere').addEventListener('click', function () {
            matiereDiv.remove();
        });
    });

    document.querySelectorAll('.supprimerMatiere').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.matiere').remove();
        });
    });
</script>

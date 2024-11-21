<section>
    <h1>Créer une agrégation</h1>
    <form method="get" action="controleurFrontal.php">
        <input type="hidden" name="action" value="creerAgregationDepuisFormulaire"/>
        <input type="hidden" name="controleur" value="agregation"/>
        <label for="nom">Nom de l'agrégation:</label>
        <input type="text" id="nom" name="nom" required>

        <label for="parcours">Parcours:</label>
        <input type="text" id="parcours" name="parcours" required>

        <h2>Sélectionnez les matières et leurs coefficients:</h2>
        <div id="matieres">
            <div class="matiere">
                <label for="matiere1">Matière:</label>
                <select name="matieres[]" required>
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($ressources as $ressource): ?>
                        <!-- Sử dụng id_ressource làm giá trị của option -->
                        <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                            <?= htmlspecialchars($ressource->getId_ressource()) ?> <!-- Hiển thị id_ressource -->
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="coefficient1">Coefficient:</label>
                <input type="number" name="coefficients[]" placeholder="Coefficient" required>
            </div>
        </div>

        <button type="button" id="ajouterMatiere">Ajouter une matière</button>
        <br><br>

        <button type="submit">Créer l'agrégation</button>
    </form>

    <script>
        document.getElementById('ajouterMatiere').addEventListener('click', function() {
            const matiereDiv = document.createElement('div');
            matiereDiv.className = 'matiere';
            matiereDiv.innerHTML = `
                <label>Matière:</label>
                <select name="matieres[]" required>
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($ressources as $ressource): ?>
                        <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                            <?= htmlspecialchars($ressource->getId_ressource()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Coefficient:</label>
                <input type="number" name="coefficients[]" placeholder="Coefficient" required>
            `;
            document.getElementById('matieres').appendChild(matiereDiv);
        });
    </script>
</section>

<section>
    <h1>Modifier une agrégation</h1>
    <form method="get" action="controleurFrontal.php">
        <input type="hidden" name="action" value="modifierAgregationDepuisFormulaire"/>
        <input type="hidden" name="controleur" value="agregation"/>
        <input type="hidden" name="id" value="<?= htmlspecialchars($agregation->getId()) ?>"/>

        <label for="nom">Nom de l'agrégation:</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($agregation->getId_ressource()) ?>" readonly>

        <label for="parcours">Parcours:</label>
        <input type="text" id="parcours" name="parcours" value="<?= htmlspecialchars($agregation->getParcours()) ?>" readonly>

        <h2>Matières existantes:</h2>
        <div id="matieresExistantes">
            <?php foreach ($matiereAgregations as $matiereAgregation): ?>
                <div class="matiere">
                    <span><strong>Matière:</strong> <?= htmlspecialchars($matiereAgregation->getNom()) ?></span>
                    <label>Coefficient:</label>
                    <input type="number" name="coefficientsExistants[<?= htmlspecialchars($matiereAgregation->getId()) ?>]"
                           value="<?= htmlspecialchars($matiereAgregation->getCoefficient()) ?>" required>
                    <button type="button" class="supprimerMatiere" data-id="<?= htmlspecialchars($matiereAgregation->getId()) ?>">Supprimer</button>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Ajouter de nouvelles matières:</h2>
        <div id="matieresAjoutees">
            <div class="matiere">
                <label>Matière:</label>
                <select name="matieresNouvelles[]">
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($ressources as $ressource): ?>
                        <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                            <?= htmlspecialchars($ressource->getNom()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Coefficient:</label>
                <input type="number" name="coefficientsNouveaux[]" placeholder="Coefficient">
            </div>
        </div>
        <button type="button" id="ajouterMatiere">Ajouter une nouvelle matière</button>
        <br><br>

        <button type="submit">Modifier l'agrégation</button>
    </form>

    <script>
        // Script để thêm mới matière
        document.getElementById('ajouterMatiere').addEventListener('click', function() {
            const matiereDiv = document.createElement('div');
            matiereDiv.className = 'matiere';
            matiereDiv.innerHTML = `
                <label>Matière:</label>
                <select name="matieresNouvelles[]">
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($ressources as $ressource): ?>
                        <option value="<?= htmlspecialchars($ressource->getId_ressource()) ?>">
                            <?= htmlspecialchars($ressource->getNom()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Coefficient:</label>
                <input type="number" name="coefficientsNouveaux[]" placeholder="Coefficient">
            `;
            document.getElementById('matieresAjoutees').appendChild(matiereDiv);
        });

        document.querySelectorAll('.supprimerMatiere').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const confirmation = confirm('Êtes-vous sûr de vouloir supprimer cette matière ?');
                if (confirmation) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'matieresASupprimer[]';
                    input.value = id;
                    this.closest('.matiere').appendChild(input);
                    this.closest('.matiere').style.display = 'none';
                }
            });
        });
    </script>
</section>

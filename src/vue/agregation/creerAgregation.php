<section>
    <h1>Créer une agrégation</h1>
    <br> <br>
    <form method="get" action="controleurFrontal.php">
        <input type="hidden" name="action" value="creerAgregationDepuisFormulaire"/>
        <input type="hidden" name="controleur" value="agregation"/>

        <label for="nom">Nom de l'agrégation:</label>
        <input type="text" id="nom" name="nom" required>

        <h3>Sélectionnez les matières et leurs coefficients:</h3>
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
        <button type="submit" class="button">Créer l'agrégation</button>
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

<style>
    section {
        padding: 5em;
    }

    select {
        width: 100%;
        padding: 8px 12px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    select:focus {
        border-color: #007bff;
        outline: none;
    }

    input[type="number"] {
        width: 100%;
        padding: 8px 12px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }


    button {
        padding: 8px 16px;
        background-color: var(--rougeUM);
        color: #fff;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }


    button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    button.supprimerMatiere {
        background-color: var(--rougeUM);
        margin-top: 10px;
    }


    #ajouterMatiere {
        display: inline-block;
        padding: 8px 16px;
        color: white;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    div {
        margin-bottom: 20px;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

</style>
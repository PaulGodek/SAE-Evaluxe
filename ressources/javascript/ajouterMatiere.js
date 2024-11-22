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

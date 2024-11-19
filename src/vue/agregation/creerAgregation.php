
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="creerAgregationDepuisFormulaire"/>
    <input type="hidden" name="controleur" value="agregation"/>
    <fieldset>
        <p>
            <label for="nom_id">Nom</label>
            <input type="text" name="nom" id="nom_id" placeholder="Cybersécurité" required maxlength="64" minlength="1"/>
        </p>
        <button type="submit">Ajouter</button>
    </fieldset>
</form>
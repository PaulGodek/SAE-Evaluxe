<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="creerEcoleDepuisFormulaire"/>
    <fieldset>
        <legend>Formulaire de création de compte école:</legend>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text"  name="login" id="login_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="nom" id="nom_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="adresse_id">Prénom&#42;</label>
            <input class="InputAddOn-field" type="text"  name="adresse" id="adresse_id" required>
        </p>
        <p class="InputAddOn">
            <input class="InputAddOn-field" type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>
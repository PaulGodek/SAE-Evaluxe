<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="creerEcoleDepuisFormulaire"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <fieldset>
        <legend>Formulaire de création de compte école:</legend>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="type_id">Type&#42;</label>
            <input class="InputAddOn-field" type="text" name="type" value="universite" id="type_id" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text" name="login" id="login_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text" name="nom" id="nom_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="adresse_id">Adresse&#42;</label>
            <input class="InputAddOn-field" type="text" name="adresse" id="adresse_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="ville_id">Ville&#42;</label>
            <input class="InputAddOn-field" type="text" name="ville" id="ville_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
        </p>
        <p class="InputAddOn">
<!--            <input class="InputAddOn-field" type="submit" value="Envoyer"/>-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>
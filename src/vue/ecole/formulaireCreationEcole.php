
<div class="container">
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="creerEcoleDepuisFormulaire"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <fieldset>
        <h2>Formulaire de création de compte école:</h2>


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
            <label class="InputAddOn-item" for="adresseMail_id">Adresse Mail&#42;</label>
            <input class="InputAddOn-field" type="text" name="adresseMail" id="adresseMail_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required>
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer"/>-->
            <button class="button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>
</div>
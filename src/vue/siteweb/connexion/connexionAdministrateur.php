<link rel="stylesheet" href="../ressources/css/connect.css">
<div class="container">
    <form method="get" action="controleurFrontal.php">
        <input type="hidden" name="action" value="connecter">
        <input type="hidden" name="controleur" value="utilisateur">
        <h2>Connexion Administrateur</h2>
        <fieldset>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="username">Identifiant&#42;</label>
                <input class="InputAddOn-field" type="text" name="login" id="username" placeholder="Identifiant"
                       required>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="password">Mot de passe &#42;</label>
                <input class="InputAddOn-field" type="password" name="password" id="password"
                       placeholder="•••••••••" required>
            <p class="InputAddOn">
                <input class="InputAddOn-field" type="submit" id="connectButton" value="Connexion"/>
            </p>
        </fieldset>

    </form>
</div>




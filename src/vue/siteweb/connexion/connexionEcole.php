

<main>
    <link rel="stylesheet" href="../ressources/css/connect.css">
    <div class="container">
        <form method="get">
            <h2>Connexion école</h2>
            <p>
                <label for="username">Identifiant</label>
                <input type="text" name="login" id="username" required placeholder="Identifiant">
            <p>
                <label for="password">Mot de passe</label>
            <div class="password-input">
                <input type="password" name="password" id="password" required placeholder="mot de passe">
            </div>
            <p>
                <input type="hidden" name="action" value="connecter">
                <input type="hidden" name="controleur" value="Connexion">
                <input type="hidden" name="type" value="ecole">
                <button type="submit" value="Connexion" id="connectButton" class="button">
                    <span>Connexion</span>
                </button>
            </p>
        </form>
    </div>
</main>



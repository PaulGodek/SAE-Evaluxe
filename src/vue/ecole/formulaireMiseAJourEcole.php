<?php

use App\GenerateurAvis\Modele\DataObject\Ecole;

/** @var Ecole $ecole */
?>

<link rel="stylesheet" href="../ressources/css/connect.css">
<div class="container">
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <input type="hidden" name="controleur" value="ecole"/>
    <fieldset>
        <h2>Formulaire de mise à jour</h2>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text" name="login" id="login_id"
                   value="<?= htmlspecialchars($ecole->getUtilisateur()->getLogin()) ?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
            <input class="InputAddOn-field" type="text" name="nom" id="nom_id"
                   value="<?= htmlspecialchars($ecole->getNom()) ?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="adresse_id">Adresse&#42;</label>
            <input class="InputAddOn-field" type="text" name="adresse" id="adresse_id"
                   value="<?= htmlspecialchars($ecole->getAdresse()) ?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="ville_id">Ville&#42;</label>
            <input class="InputAddOn-field" type="text" name="ville" id="ville_id"
                   value="<?= htmlspecialchars($ecole->getVille()) ?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="email_id">Email&#42;</label>
            <input class="InputAddOn-field" type="text" name="email" id="email_id"
                   value="<?= htmlspecialchars($ecole->getAdresseMail()) ?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp_id">Nouveau Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp" id="nvmdp_id" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp2_id">Vérification du nouveau mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp2" id="nvmdp2_id" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required>
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class="button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>
</div>
<?php
use App\GenerateurAvis\Modele\DataObject\Administrateur;
/** @var Administrateur $admin */
?>
<link rel="stylesheet" href="../ressources/css/connect.css">
<div class="container">
<form method="get" action="controleurFrontal.php">
    <input type="hidden" name="action" value="mettreAJour"/>
    <input type="hidden" name="controleur" value="administrateur">
    <fieldset>
        <h2>Formulaire de mise à jour </h2>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="login_id">Login&#42;</label>
            <input class="InputAddOn-field" type="text" name="login" id="login_id" value="<?= htmlspecialchars($admin->getAdministrateur()->getLogin())?>" readonly>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mail_id">Email&#42;</label>
            <input class="InputAddOn-field" type="text"  name="email" id="mail_id" value="<?= htmlspecialchars($admin->getAdresseMail())?>" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp" id="nvmdp_id" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nvmdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="nvmdp2" id="nvmdp2_id"  pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}"
                   title="Le mot de passe doit contenir entre 8 et 16 caractères, avec au moins une minuscule, une majuscule, un chiffre et un caractère spécial." required >
        </p>
        <p class="InputAddOn">
            <!--            <input class="InputAddOn-field" type="submit" value="Envoyer" />-->
            <button class = "button-submit" type="submit">Envoyer</button>
        </p>
    </fieldset>
</form>
</div>
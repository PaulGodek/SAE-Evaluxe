<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php /** @var string $titre */
        echo $titre; ?></title>
    <link rel="stylesheet" type="text/css" href="../ressources/css/navstyle.css">

</head>
<body>
<header>

</header>
<main>
    <?php
    /** @var string $cheminCorpsVue */
    require __DIR__ . "/{$cheminCorpsVue}";
    ?>
</main>
<footer>
    <p>
        Futur site de généréation d'avis de l'IUT, réalisé par GlobalEduTech
    </p>
</footer>
</body>
</html>
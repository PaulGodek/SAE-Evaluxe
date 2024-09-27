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
            <nav>
                <ul>
                    <li>
                        <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Gestion des utilisateurs</a>
                    </li><li>
                        <a href="controleurFrontal.php?action=afficherListe&controleur=trajet">Gestion des trajets</a>
                    </li>
                </ul>
            </nav>


        </header>
        <main>
            <?php
            /** @var string $cheminCorpsVue */
            require __DIR__ . "/{$cheminCorpsVue}";
            ?>
        </main>
        <footer>
            <p>
                Super site de covoiturage de Paul Godek, le goat originel
            </p>
        </footer>
    </body>
</html>
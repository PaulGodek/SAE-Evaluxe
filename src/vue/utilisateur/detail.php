<?php
/** @var Utilisateur $utilisateur */

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

echo "L'utilisateur " . htmlspecialchars($utilisateur->getLogin()) . " est de type " . htmlspecialchars($utilisateur->getType()) . ".";
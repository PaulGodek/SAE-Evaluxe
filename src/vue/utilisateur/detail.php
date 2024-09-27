<?php
/** @var Utilisateur $utilisateur */

use App\GenerateurAvis\Modele\DataObject\Utilisateur;

echo "Le login de l'utilisateur " . htmlspecialchars($utilisateur->getPrenom()) . " " . htmlspecialchars($utilisateur->getNom()) . " est " . htmlspecialchars($utilisateur->getLogin()) . ".";
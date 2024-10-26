<?php
/** @var Professeur $professeur */

use App\GenerateurAvis\Modele\DataObject\Professeur;

echo "Le professeur " . htmlspecialchars($professeur->getNom()) . " " . htmlspecialchars($professeur->getPrenom()) . ".";
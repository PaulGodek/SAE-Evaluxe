<?php
/** @var Ecole $ecole */

use App\GenerateurAvis\Modele\DataObject\Ecole;

echo "L'école " . htmlspecialchars($ecole->getNom()) . " se trouve au " . htmlspecialchars($ecole->getAdresse()) . ".";
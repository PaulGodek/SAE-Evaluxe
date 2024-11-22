<?php

namespace App\GenerateurAvis\Modele\Repository;

use App\GenerateurAvis\Modele\DataObject\AbstractDataObject;
use App\GenerateurAvis\Modele\DataObject\Administrateur;

class AdministrateurRepository extends AbstractRepository
{
    private static string $tableAdmin = "AdminTest";

    protected function getNomTable(): string
    {
        return "AdminTest";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function construireDepuisTableauSQL(array $adminFormatTableau): AbstractDataObject
    {
        return new Administrateur(
            (new UtilisateurRepository())->recupererParClePrimaire($adminFormatTableau["login"]),
            $adminFormatTableau['adresseMail']
        );
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "adresseMail"];
    }

    protected function formatTableauSQL(AbstractDataObject $admin): array
    {
        return array(
            "loginTag" => $admin->getAdministrateur()->getLogin(),
            "adresseMailTag" => $admin->getAdresseMail()
        );
    }
}
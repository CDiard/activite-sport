<?php

namespace App\Controller\Admin;

use App\Entity\Equipe;
use App\Entity\Joueur;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class JoueurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Joueur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('username')->setLabel('Pseudonyme'),
            Field::new('equipe.nom')->setLabel('Equipe'),
            FieldCollection::new()
        ];
    }

}

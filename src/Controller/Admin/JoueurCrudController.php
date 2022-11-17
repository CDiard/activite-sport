<?php

namespace App\Controller\Admin;

use App\Entity\Equipe;
use App\Entity\Joueur;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class JoueurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Joueur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield Field::new('username')->setLabel('Pseudonyme'),
            yield Field::new('equipe.nom')->setLabel('Equipe')->hideOnForm(),
            yield AssociationField::new('Equipe', 'Equipe')->renderAsNativeWidget(Equipe::class),

        ];
    }

}

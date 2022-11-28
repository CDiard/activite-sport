<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;


class JoueurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Player::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            yield Field::new('pseudo')->setLabel('Pseudonyme'),
            yield Field::new('team.name')->setLabel('Equipe')->hideOnForm(),
           // yield AssociationField::new('Team', 'Equipe')->renderAsNativeWidget(Team::class),
            yield AssociationField::new('team')->renderAsNativeWidget(Team::class),
        ];
    }

}

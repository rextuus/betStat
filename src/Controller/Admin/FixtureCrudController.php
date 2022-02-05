<?php

namespace App\Controller\Admin;

use App\Entity\Fixture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FixtureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Fixture::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

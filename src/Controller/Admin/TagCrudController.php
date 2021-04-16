<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de la catégorie'),
            SlugField::new('slug','Permalien')->setTargetFieldName('name'),
            ChoiceField::new('type', 'Type')->setChoices(
                ['Pièce' => 'Pièce',
                    'Style' => 'Style'
                ]
            ),
            SlugField::new('slugType','Permalien du type')->setTargetFieldName('type')->onlyOnForms(),
        ];
    }
}

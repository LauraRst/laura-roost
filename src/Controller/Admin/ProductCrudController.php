<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageProductType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setSearchFields(['name'])
            ->setDefaultSort(['id' => 'DESC'])

            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex()->setFormTypeOption('disabled','disabled'),
            FormField::addPanel('Détails du produit'),
            BooleanField::new('is_inStock', 'En stock'),
            TextField::new('name', 'Nom '),
            SlugField::new('slug', 'Permalien')->setTargetFieldName('name')->onlyOnForms(),
            TextEditorField::new('description','Description')->setSortable(false),
            IntegerField::new('price', 'Prix')->setSortable(false),
            PercentField::new('sale', 'Réduction')->setStoredAsFractional(false),
            PercentField::new('tva', 'TVA')
                ->setStoredAsFractional(false)
            ->setSortable(false),
            FormField::addPanel('Informations supplémentaires'),
            AssociationField::new('category', 'Catégorie'),
            AssociationField::new('tag', 'Etiquette')->hideOnIndex(),
            TextField::new('weight', 'Poids (kg)')->hideOnIndex(),
            TextField::new('dimensions', 'Dimension (cm)')->setHelp('ex : H83 x L214 x PR86')->hideOnIndex(),
            FormField::addPanel('Images du produit'),
            CollectionField::new('image', 'Image')
                ->hideOnIndex()
                ->setTranslationParameters(['form.label.delete'=>'Supprimer l\'image'])
                ->setEntryType(ImageProductType::class)
            ,

        ];
    }
}

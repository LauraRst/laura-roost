<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']);
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] =   IdField::new('id', 'Numéro de commande')
            ->onlyOnDetail()
            ->onlyOnIndex()
            ->setFormTypeOption('disabled','disabled');
        $fields[] = DateField::new('createdAt', 'Date commande');

        $fields[] = ChoiceField::new('status', 'Statut')->setChoices(
            ['En cours de préparation' => 'En cours de préparation',
                'Expediée' => 'Expediée',
                'En cours de livraison' => 'En cours de livraison',
                'Livrée' => 'Livré',
                 'Panier' => 'Cart'
            ]
        );
        $fields[] =  AssociationField::new('carts', 'Produits');
        $fields[] = IntegerField::new('totalOrder', 'Total');
        $fields[] =  AssociationField::new('user', 'Client')->setSortable(false);

        return $fields;
    }



}

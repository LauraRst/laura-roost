<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] =   IdField::new('id', 'Numéro de commande')
            ->onlyOnDetail()
            ->onlyOnIndex();
        $fields[] = ChoiceField::new('status', 'Statut')->setChoices(
            ['En cours de préparation' => 'En cours de préparation',
                'Expediée' => 'Expediée',
                'En cours de livraison' => 'En cours de livraison',
                'Livrée' => 'Livré',
                 'Panier' => 'Cart'
            ]
        );
        $fields[] =  AssociationField::new('carts', 'Produits');
        $fields[] =  AssociationField::new('user', 'Client')
            ->onlyOnDetail()
            ->onlyOnIndex()
            ->setSortable(false);

        return $fields;
    }



}

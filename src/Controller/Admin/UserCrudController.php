<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setFormTypeOption('disabled','disabled'),
            DateField::new('registration', 'Inscription'),
            TextField::new('username', 'Identifiant'),
            TextField::new('email'),
            TextField::new('title', 'Civilité')
                ->onlyOnDetail()
                ,
            TextField::new('firstname', 'Prénom')
                ->onlyOnDetail()
                ,
            TextField::new('lastname', 'Nom'),
            DateField::new('birthdate', "Date de naissance")->hideOnIndex(),
            TextField::new('phone', 'Téléphone')->setSortable(false),
            TextField::new('address', 'Adresse')->hideOnIndex(),
            TextField::new('zipcode', "Code postal")->hideOnIndex(),
            TextField::new('city', 'Ville')->hideOnIndex(),
            BooleanField::new('isSubscribed', 'Newsletter')->hideOnIndex(),
            BooleanField::new('isContactMail', 'Contact par mail')->hideOnIndex(),
            BooleanField::new('isContactSms', 'contact par sms')->hideOnIndex()


        ];
    }


}

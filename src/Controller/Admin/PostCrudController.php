<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image')->setBasePath('/images/blog')->onlyOnIndex(),
            BooleanField::new('isPublished', 'Afficher sur le site')->onlyOnForms(),
            TextField::new('title', 'Titre'),
            SlugField::new('slug', 'Permalien')->setTargetFieldName('title')->onlyOnForms(),
            TextEditorField::new('content', 'Contenu')->onlyOnForms(),
            DateField::new('createdAt', 'Publié le'),
            DateField::new('updatedAt', 'Mis à jour le')->onlyWhenUpdating(),
            TextareaField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->onlyOnForms()
                ->setTranslationParameters(['form.label.delete'=>'Supprimer l\'image'])
            ,


        ];
    }
}

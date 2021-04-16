<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('carts', CollectionType::class, [
              'entry_type' => CartType::class
          ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Valider ma commande'
            ])
            ->add('update', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Mettre à jour mon panier'
            ])
            ->add('delete', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary'],
                'label' => 'Tout enlever'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}

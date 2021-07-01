<?php

namespace App\Form\Type;

use App\Entity\Person;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonLikeProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', EntityType::class, [
                'class' => Person::class,
                'multiple' => false,
                'label' => 'Użytkownik',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'multiple' => false,
                'label' => 'Produkt',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz',
                'attr' => [
                    'class' => 'mt-2 btn btn-sm btn-primary'
                ]
            ])
        ;
    }
}

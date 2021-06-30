<?php

namespace App\Form\Type;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Imię'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko'
            ])
            ->add('state', ChoiceType::class, [
                'choices' => array_flip(Person::getStateSelectionMethodLabels()),
                'label' => 'Imię'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}

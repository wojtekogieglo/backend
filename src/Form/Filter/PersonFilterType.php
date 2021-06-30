<?php

namespace App\Form\Filter;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
                'required' => false
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'required' => false
            ])
            ->add('state', ChoiceType::class, [
                'choices' => array_flip(Person::getStateSelectionMethodLabels()),
                'label' => 'Status',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtruj'
            ])
            ->setMethod('GET')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rendu', CheckboxType::class, [
                'label' => 'Emprunt en cours',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'data' => true,

            ])
            ->add('date_emprunt', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date d'emprunt",
                'required' => true,
                // or false, depending on your needs
                // Additional options if needed
            ])
            ->add('date_fin_prevue', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour',
                'required' => true,
                // or false, depending on your needs
                // Additional options if needed
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

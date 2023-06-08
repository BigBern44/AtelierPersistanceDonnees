<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    private AuteurRepository $auteurRepository;
    private CategorieRepository $categorieRepository;

    public function __construct(AuteurRepository $auteurRepository, CategorieRepository $categorieRepository)
    {
        $this->auteurRepository = $auteurRepository;
        $this->categorieRepository = $categorieRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $auteurs = $this->auteurRepository ->findAll();
        $categories = $this->categorieRepository->findAll();

        $builder

            ->add('titre', TextType::class, [])
            ->add('date_de_parution', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => true,
                // or false, depending on your needs
                // Additional options if needed
            ])
            ->add('nombre_de_pages')
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => 'disponible',
                    'Indisponible' => 'indisponible',
                ],
                'placeholder' => 'Choisir le statut',
                'required' => true,
            ])
            ->add('Auteur', EntityType::class, [
                'label' => 'Auteur',
                'class' => Auteur::class,
                'choices' => $auteurs,
                'choice_label' => function ($auteur) {
                    return $auteur->getNom() . ' ' . $auteur->getPrenom();
                },
                'placeholder' => 'Choisir un auteur', // Optional placeholder text
                'required' => false, // Set to true if the field is required
            ])

            ->add('Categorie', EntityType::class, [
                'label' => 'Categorie',
                'class' => Categorie::class,
                'choices' => $categories,
                'choice_label' => "nom",
                'multiple' => true,
                'expanded' => true,
                'placeholder' => 'Choisir une auteur', // Optional placeholder text
                'required' => false, // Set to true if the field is required
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}

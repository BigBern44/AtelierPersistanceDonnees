<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\AdherentRepository;
use App\Repository\AuteurRepository;
use App\Repository\LivreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class EmpruntType extends AbstractType
{
    private LivreRepository $livreRepository;
    private AdherentRepository $adherentRepository ;

    public function __construct(LivreRepository $livreRepository, AdherentRepository $adherentRepository)
    {
        $this->adherentRepository = $adherentRepository;
        $this->livreRepository = $livreRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $livres = $this->livreRepository->findBy(['statut' => 'disponible']);
        $adherents = $this->adherentRepository->findAll();
        $builder
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


            ->add('livres', EntityType::class, [
                'label' => 'Livres',
                'class' => Livre::class,
                'choices' => $livres,
                'choice_label' => 'titre',
                'multiple' => true,
                'placeholder' => 'Choisir un livre', // Optional placeholder text
                'required' => false, // Set to true if the field is required
            ])

            ->add('adherent', EntityType::class, [
                'label' => 'Adherent',
                'class' => Adherent::class,
                'choices' => $adherents,
                'choice_label' => function ($adherent) {
                return $adherent->getNom() . ' ' . $adherent->getPrenom();
                },
                'placeholder' => 'Choisir un adherent', // Optional placeholder text
                'required' => false, // Set to true if the field is required
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}

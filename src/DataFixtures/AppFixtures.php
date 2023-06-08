<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\AdherentRepository;
use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use App\Repository\LivreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    protected $faker;
    protected $auteurRepository;
    protected $livreRepository;
    function __construct(AuteurRepository $auteurRepository, AdherentRepository $adherentRepository, LivreRepository $livreRepository, CategorieRepository $categorieRepository)
    {
        $this->faker = Factory::create();
        $this->auteurRepository = $auteurRepository;
        $this->adherentRepository = $adherentRepository;
        $this->livreRepository = $livreRepository;
        $this->categorieRepository = $categorieRepository;

    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Science-fiction',
            'Romance',
            'Mystère',
            'Fantasy',
            'Thriller',
            'Historique',
            'Jeunesse',
            'Biographie',
            'Horreur',
            'Poésie',
        ];

        foreach ($categories as $categoryName) {
            $category = new Categorie();
            $category->setNom($categoryName);

            $manager->persist($category);
        }

        $manager->flush();

        $startDate = strtotime('1980-01-01');
        $endDate = strtotime('2022-12-31');

        for ($i = 0; $i < 50; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->lastName);
            $auteur->setPrenom($this->faker->firstName);
            $manager->persist($auteur);
        }
        $manager->flush();

        for($i= 0; $i<200; $i++){
            $livre = new Livre();
            $livre->setAuteur($this->getRandomAuteur());
            $randomTimestamp = rand($startDate, $endDate);
            $randomDate = date('Y-m-d', $randomTimestamp);
            $livre->setDateDeParution(new \DateTime($randomDate));
            $livre->setNombreDePages(random_int(50,350));
            $livre->setStatut("disponible");
            $livre->setTitre($this->faker->sentence($nbWords = random_int(1,3)));
            $categorie = $this->getRandomCategorie();
            $livre->addCategorie($categorie);
            $manager->persist($categorie);
            $manager->persist($livre);
            $manager->flush();
        }

        for($i= 0; $i<100; $i++){
            $adherent = new Adherent();
            $adherent->setPrenom($this->faker->firstName);
            $adherent->setNom($this->faker->name);
            $randomTimestamp = rand($startDate, $endDate);
            $randomDate = date('Y-m-d', $randomTimestamp);
            $adherent->setDateInscription(new \DateTimeImmutable($randomDate));
            $adherent->setEmail($this->faker->email);
            $manager->persist($adherent);
        }
        $manager->flush();

        $startDateEmprunt = strtotime('2020-01-01');
        $endDateEmprunt = strtotime('2023-12-31');

        for($i= 0; $i<20; $i++){
            $emprunt = new Emprunt();
            $emprunt->setAdherent($this->getRandomAdhrent());
            $randomTimestamp = rand($startDateEmprunt, $endDateEmprunt);
            $randomDate = date('Y-m-d', $randomTimestamp);
            $emprunt->setDateEmprunt(new \DateTime($randomDate));
            $randomTimestamp = rand($randomTimestamp, $endDateEmprunt);
            $randomDate = date('Y-m-d', $randomTimestamp);
            $emprunt->setDateFinPrevue(new \DateTime($randomDate));

            $livre= $this->getRandomLivreDispo();
            $livre->setStatut("non disponible");
            $emprunt->addLivre($livre);
            $manager->persist($livre);
            $manager->persist($emprunt);

            $manager->flush();
        }




    }
    public function getRandomCategorie() : Categorie
    {
        $listCategorie = $this->categorieRepository->findAll();
        $randInt = random_int(0,sizeof($listCategorie)-1);
        return $randCategorie = $listCategorie[$randInt];
    }

    public function getRandomAuteur() : Auteur
    {
        $listAuteur = $this->auteurRepository->findAll();
        $randInt = random_int(0,sizeof($listAuteur)-1);
        return $randAuteur = $listAuteur[$randInt];
    }

    public function getRandomAdhrent() : Adherent
    {
        $listAdherent = $this->adherentRepository->findAll();
        $randInt = random_int(0,sizeof($listAdherent)-1);
        return $randAdherent = $listAdherent[$randInt];
    }

    public function getRandomLivreDispo() : Livre
    {
        $listLivre = $this->livreRepository->findBy(['statut' => 'disponible']);
        $randInt = random_int(0,sizeof($listLivre)-1);
        return $randLivre = $listLivre[$randInt];
    }




}

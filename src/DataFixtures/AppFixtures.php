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
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use GuzzleHttp\Client;
use PhpParser\Node\Expr\Array_;

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
        $client = new Client();

        // Make a request to the Google Books API to retrieve novels with thumbnail images
        $response = $client->get('https://www.googleapis.com/books/v1/volumes?q=subject:fiction&printType=books&maxResults=40');

        $data = json_decode($response->getBody(), true);

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

        }
        $manager->flush();

        foreach ($data['items'] as $item) {

            $livre = new Livre();
            $auteur = $this->getNewAuthor($item['volumeInfo']['authors'][0] ?? $this->faker->name);

            $livre->setAuteur($auteur);
            $randomTimestamp = rand($startDate, $endDate);
            $randomDate = date('Y-m-d', $randomTimestamp);
            $livre->setDateDeParution(new \DateTime($item['volumeInfo']['publishedDate'] ?? $randomDate));
            $livre->setNombreDePages($item['volumeInfo']['pageCount'] ?? random_int(54,356)); // Use null if 'pageCount' is not defined
            $livre->setImageUrl($item['volumeInfo']['imageLinks']['thumbnail']);
            $livre->setStatut("disponible");
            $livre->setTitre($item['volumeInfo']['title']);
            $categorie = $this->getRandomCategorie();
            $livre->addCategorie($categorie);
            $manager->persist($auteur);
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

    public function getNewAuthor(string $auteurNom) : Auteur
    {
        $auteur = new Auteur();
        $auteur->setNom($auteurNom);
        return $auteur;
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

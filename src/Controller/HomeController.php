<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, LivreRepository $livreRepository): Response
    {

        $searchTerm = $request->query->get('search');


        if($searchTerm!=null){

            $query = $this->entityManager->createQueryBuilder()
                ->select('livre')
                ->from('App\Entity\Livre', 'livre')
                ->join('livre.Auteur', 'auteur') // Utilisation de la casse correcte
                ->where('auteur.nom LIKE :authorName OR auteur.prenom LIKE :authorName')
                ->setParameter('authorName',  '%' . $searchTerm . '%')
                ->getQuery();

            $livres = $query->getResult();

        }else{
            $livres = $livreRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'livres' => $livres,
            'search' => $searchTerm
        ]);
    }
}

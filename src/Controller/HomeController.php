<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, LivreRepository $livreRepository, PaginatorInterface $paginator): Response
    {

        $searchTerm = $request->query->get('search');


        if($searchTerm!=null){

            $query = $this->entityManager->createQueryBuilder()
                ->select('livre')
                ->from('App\Entity\Livre', 'livre')
                ->join('livre.auteur', 'auteur') // Utilisation de la casse correcte
                ->where('auteur.nom LIKE :authorName')
                ->setParameter('authorName',  '%' . $searchTerm . '%')
                ->getQuery();


        }else{
            $query = $this->entityManager->createQueryBuilder()
                ->select('livre')
                ->from('App\Entity\Livre', 'livre')
                ->getQuery();

        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'pagination' => $pagination,
            'search' => $searchTerm
        ]);
    }
}

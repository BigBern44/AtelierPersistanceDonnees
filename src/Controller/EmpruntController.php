<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Form\SearchCategorieType;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emprunt')]
class EmpruntController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_emprunt_index', methods: ['GET','POST'])]
    public function index(Request $request, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createForm(SearchCategorieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $emprunts = array();


            $query = $this->entityManager->createQueryBuilder()
                ->select('emprunt')
                ->from('App\Entity\Emprunt', 'emprunt')
                ->where('emprunt.date_emprunt > :dateEmprunt AND emprunt.date_fin_prevue < :dateRetour')
                ->setParameter('dateEmprunt',  $formData['date_emprunt'] )
                ->setParameter('dateRetour',  $formData['date_fin_prevue'] )
                ->getQuery();

            $emprunts += $query->getResult();
            if( $formData['rendu'] == true){
                foreach ( $emprunts as $emprunt){
                    if ($emprunt->getDateRetour() != null){
                        $emprunts = array_filter($emprunts, function ($value) use ($emprunt) {
                            return $value !== $emprunt;
                        });
                    }
                }
            }

        }else{
            $emprunts = $empruntRepository->findAll();
        }

        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $emprunts,
            'form' => $form->createView()
        ]);
    }

    #[Route('/retour/{id}', name: 'app_emprunt_retour', methods: ['GET', 'POST'])]
    public function retour(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository, LivreRepository $livreRepository): Response
    {
        $emprunt->setDateRetour(new \DateTime('now'));
        $livres = $emprunt->getLivres();
        foreach ($livres as $livre){
            $livre->setStatut("disponible");
            $livreRepository->save($livre, true);
        }
        $empruntRepository->save($emprunt, true);
        return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);

    }



    #[Route('/new', name: 'app_emprunt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmpruntRepository $empruntRepository, LivreRepository $livreRepository): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livres = $emprunt->getLivres();

            foreach ($livres as $livre){
                $livre->setStatut("non disponible");
                $livre->addEmprunt($emprunt);
                $livreRepository->save($livre, true);
            }

            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunt/new.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_emprunt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository, LivreRepository $livreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->request->get('_token'))) {
            $livres = $emprunt->getLivres();
            foreach ($livres as $livre){
                $livre->setStatut("disponible");
                $livre->addEmprunt($emprunt);
                $livreRepository->save($livre, true);
            }
            $empruntRepository->remove($emprunt, true);

        }

        return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Journee;
use App\Repository\JourneeRepository;
use App\Repository\SessionsJeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SessionsController extends AbstractController
{
    #[Route('/sessions', name: 'sessions')]
    public function index(JourneeRepository $journeeRepository): Response
    {
        $derniereJournee=$journeeRepository->findOneBy([],['id'=>'DESC']);
        $aujourdhui = new \DateTimeImmutable('today');
        $statut=$derniereJournee->isStatut();
         
        $DejaOuvert = false;
        if ($derniereJournee && $derniereJournee->getDate()->format('Y-m-d') === $aujourdhui->format('Y-m-d') && $statut===true) {
            $DejaOuvert = true;
            // dd($derniereJournee,$statut);
            }
        return $this->render('sessions/index.html.twig', [
            'journee' => $derniereJournee,
            'DejaOuvert'=>$DejaOuvert
        ]);
    }
    #[Route('/new_journee', name:'new_journee')]
    public function journee(EntityManagerInterface $entityManager,):Response
    {
        $journee= new Journee();
        $budget= 0;
            $journee->setCreatedAt(new \DateTimeImmutable());
            $journee->setDate(new \DateTime);
            $journee->setCaisseOuverture($budget);
            $journee->setStatut(true);
            $entityManager->persist($journee);
            $entityManager->flush();
            

            return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);

        return $this->render('sessions/index.html.twig', [
           
        ]);   
    }
    #[Route('/close_journee/{id}', name:'close_journee')]
    public function closeJournee(Request $request,Journee $journee, EntityManagerInterface $entityManager)
    {
        $journee->getId();
        $journee->setStatut(false);
        $entityManager->persist($journee);
        $entityManager->flush();

        return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
    }
}

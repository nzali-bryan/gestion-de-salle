<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\DepenseType;
use App\Repository\JourneeRepository;
use App\Repository\TransactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    #[Route('/transaction', name: 'transaction')]
    public function index(Request $request,JourneeRepository $journeeRepository,  TransactionRepository $transactionRepository , EntityManagerInterface $em, TransactionRepository $TR,): Response
    {
        $depense= new Transaction();
        $form= $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            // $depense->setCreatadAt( new \DateTimeImmutable());
            $em->persist($depense);
            $em->flush();
        }
        $dernireTrans=$transactionRepository->findDerniereTrans();
        $choixTotal = $transactionRepository->getDepRev();
        $totalEntrant = 0;
        $totalSortant = 0;
        $stats=$transactionRepository->findRevenusParConsole();
    // dd($stats);
        foreach ($choixTotal as $ligne) {
            if ($ligne['type'] === 'entrée') {
                $totalEntrant = (int) $ligne['total'];
            } elseif ($ligne['type'] === 'sortie') {
                $totalSortant = (int) $ligne['total'];
            }
        }
        //  filtrage plus personalisés du bilan 
    $dateDebutStr = $request->query->get('date_debut');
    $dateFinStr = $request->query->get('date_fin');

    if ($dateDebutStr && $dateFinStr) {
        $dateDebut = new \DateTimeImmutable($dateDebutStr);
        $dateFin = new \DateTimeImmutable($dateFinStr);
    } else {
        $dateDebut = new \DateTimeImmutable('-30 days');
        $dateFin = new \DateTimeImmutable('now');
    }

    $bilan = $transactionRepository->findBilan($dateDebut, $dateFin);


        //  filtrage plus personalisés du bilan 
         $derniereJournee=$journeeRepository->findOneBy([],['id'=>'DESC']);
        return $this->render('transaction/index.html.twig', [
            'derniereTrans'=>$dernireTrans,
            'totalEntrant'=>$totalEntrant,
            'totalSortant'=>$totalSortant,
           'journee' => $derniereJournee,
           'form'=>$form,
           'statistiques' =>$stats,
           'bilan' => $bilan,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,

        ]);

        
    }
    // #[Route ('/new_depense', name:'new_depense')]
    // public function depense(Request $request, Transaction $Transaction, EntityManagerInterface $em): Response
    // {
    //     $depense= new Transaction();
    //     $form= $this->createForm(DepenseType::class, $Transaction);
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()) { 
    //         $depense->setCreatadAt( new \DateTimeImmutable());
    //         $em->persist($depense);
    //         $em->flush();
    //     }

    // }
}

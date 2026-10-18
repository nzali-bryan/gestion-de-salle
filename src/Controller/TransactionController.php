<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\DepenseType;
use App\Repository\JourneeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    #[Route('/transaction', name: 'transaction')]
    public function index(Request $request,JourneeRepository $journeeRepository, EntityManagerInterface $em): Response
    {
        $depense= new Transaction();
        $form= $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $depense->setCreatadAt( new \DateTimeImmutable());
            $em->persist($depense);
            $em->flush();
        }

         $derniereJournee=$journeeRepository->findOneBy([],['id'=>'DESC']);
        return $this->render('transaction/index.html.twig', [
           'journee' => $derniereJournee,
           'form'=>$form
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

<?php

namespace App\Controller;

use App\Repository\JourneeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(JourneeRepository $journeeRepository): Response
    {
        $journee=$journeeRepository->findOneBy([],['id'=>'DESC']);

        return $this->render('dashboard/index.html.twig', [
            'journee'=>$journee,
        ]);
    }
}

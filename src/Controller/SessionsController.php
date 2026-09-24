<?php

namespace App\Controller;

use App\Entity\Console;
use App\Entity\Journee;
use App\Entity\SessionsJeu;
use App\Form\SessionsType;
use App\Repository\ConsoleRepository;
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
    public function index(Request $request, EntityManagerInterface $entityManager, JourneeRepository $journeeRepository, ConsoleRepository $consoleRepository): Response
    {
        $derniereJournee=$journeeRepository->findOneBy([],['id'=>'DESC']);
        $consoles=$consoleRepository->findAll();
        $forms=[];
        // formulaire pour ouvrir une session pour chaque console
        foreach ($consoles as $console){
            $sessions = new SessionsJeu();
            $debut = new \DateTime();
    
        // On crée un formulaire unique pour cette console
        // 'form_console_' . $console->getId() permet de donner un nom unique au formulaire en POST

            $form = $this->createForm(SessionsType::class, $sessions);
            $form->handleRequest($request);
            $form=$this->container->get('form.factory')->createNamed('form_console_' . $console->getId(), SessionsType::class, $sessions);
        }




        if ($form->isSubmitted() && $form->isValid()) {
            $dureeEnMinutes = $form->get('duree')->getData();
            // pour savoir a quel l'heure finis la sessions 
            $fin = $debut->modify('+' . $dureeEnMinutes . ' minutes');
            // dd($fin);
            $sessions->setFin($fin);
            $sessions->setStatut('active');
            $sessions->setDebut(new \DateTime());
            $sessions->setJournee($derniereJournee); 
            $entityManager->persist($sessions);
            $entityManager->flush();
            $console = $sessions->getConsole();
            // dd($console);
            // changer l'état de la console après avoir crée une session
            if ($console) {
                $console->setStatut('En jeu'); 
                $entityManager->persist($console); 
            }
            $entityManager->persist($console);
            $entityManager->flush();
            

            return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
        }
        
        // le nombre de console en jeu et
        $aujourdhui = new \DateTimeImmutable('today');
        $statut=$derniereJournee->isStatut();
        $console=$consoleRepository->findAll();
        $totalConsole=$consoleRepository->count([]);
        $totalEnJeu = $consoleRepository->count(['Statut' => 'En jeu']);
        $totalLibre = $consoleRepository->count(['Statut' => 'Libre']);
         
        $DejaOuvert = false;
        if ($derniereJournee && $derniereJournee->getDate()->format('Y-m-d') === $aujourdhui->format('Y-m-d') && $statut===true) {
            $DejaOuvert = true;
            // dd($derniereJournee,$statut);
        }


        return $this->render('sessions/index.html.twig', [
            'journee' => $derniereJournee,
            'DejaOuvert'=>$DejaOuvert,
            'Consoles'=>$console,
            'totalConsole'=>$totalConsole,
            'totalEnJeu'=>$totalEnJeu,
            'totalLibre'=>$totalLibre,
            'form'=>$form,
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
    // fermer la journée
    #[Route('/close_journee/{id}', name:'close_journee')]
    public function closeJournee(Request $request,Journee $journee, EntityManagerInterface $entityManager)
    {
        $journee->getId();
        $journee->setStatut(false);
        $entityManager->persist($journee);
        $entityManager->flush();

        return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
    }
    // fermer la session
        #[Route('/close_console/{id}', name:'close_console')]
    public function closeSessions(Request $request,Console $console,SessionsJeuRepository $sessionsJeuRepository, EntityManagerInterface $entityManager)
    {
        // ça regarde la dernière session active
        $derniereSession = $sessionsJeuRepository->findOneBy(
        ['Console' => $console],
        ['id' => 'DESC']         // Tri : par ID décroissant
    );

        // dd($derniereSession);
        if ($derniereSession){
            $derniereSession->setStatut('Libre');
            $console=$derniereSession->getConsole();
            $console->setStatut('Libre');
            $entityManager->persist($derniereSession);
            $entityManager->persist($console);
            $entityManager->flush();
        }


        return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
    }
}

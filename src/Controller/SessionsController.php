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

            $form=$this->container->get('form.factory')->createNamed('form_console_' . $console->getId(), SessionsType::class, $sessions);
            $form->handleRequest($request);

            // mes conditions de la session
            if ($form->isSubmitted() && $form->isValid()) {
            $dureeEnMinutes = $form->get('duree')->getData();

            // pour savoir a quel l'heure finis la sessions 
            $fin = $debut->modify('+' . $dureeEnMinutes . ' minutes');
            $sessions->setFin($fin);
            $sessions->setStatut('active');
            $sessions->setDebut(new \DateTime());
            $sessions->setJournee($derniereJournee); 
            $entityManager->persist($sessions);
            $entityManager->flush();
            $console = $sessions->getConsole();

            // changer l'état de la console après avoir crée une session
            if ($console) {
                $console->setStatut('En jeu'); 
            }
            $entityManager->persist($console);
            $entityManager->flush();
            return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
        }
        // stockage du formulaire avec l'Id de la console pour qui va aider dans le twig
        $forms[$console->getId()] = $form->createView();

        }

        // le nombre de console en jeu et
        $aujourdhui = new \DateTimeImmutable('today');
        $statut=$derniereJournee->isStatut();
        $totalConsole=$consoleRepository->count([]);
        $totalEnJeu = $consoleRepository->count(['Statut' => 'En jeu']);
        $totalLibre = $consoleRepository->count(['Statut' => 'Libre']);
         
        $DejaOuvert = false;
        if ($derniereJournee && $statut===true) {
            $DejaOuvert = true;
        }

        // dump($consoles);
        return $this->render('sessions/index.html.twig', [
            'journee' => $derniereJournee,
            'DejaOuvert'=>$DejaOuvert,
            'Consoles'=>$consoles,
            'totalConsole'=>$totalConsole,
            'totalEnJeu'=>$totalEnJeu,
            'totalLibre'=>$totalLibre,
            'forms'=>$forms,
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
        #[Route('/close_session/{id}', name:'close_console')]
    public function closeSessions(Request $request,Console $console,SessionsJeuRepository $sessionsJeuRepository, EntityManagerInterface $entityManager)
    {
        // ça regarde la derniere session active
        $derniereSession = $sessionsJeuRepository->findOneBy(
        ['Console' => $console],
        ['id' => 'DESC']         
    );

        // dd($derniereSession);
        if ($derniereSession){
            $derniereSession->setStatut('Fermer');
            $console=$derniereSession->getConsole();
            $console->setStatut('Libre');
            $entityManager->persist($derniereSession);
            $entityManager->persist($console);
            $entityManager->flush();
        }


        return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('add_time/{id}' , name:'add_time')]
    public function AddTime(Request $request, Console $console, ConsoleRepository $consoleRepository, EntityManagerInterface $entityManager,sessionsJeuRepository $sessionsJeuRepository):Response
    {

        $derniereSession = $sessionsJeuRepository->findOneBy(
        ['Console' => $console],
        ['id' => 'DESC']         
    );
    
            if ($derniereSession){

            // clone est un attribut très important sur symfony car quand tu utilise le date time si tu ne fais pas ça le Flush ne va pas marcher 
            $heureFin= clone $derniereSession->getFin();
            $heureFin=$heureFin->modify('+'. 15 .' minutes');
            $derniereSession->setFin($heureFin);

            // maintenant je j'augmente l'argent
            $argentSession=$derniereSession->getMontant();
            $argentSession=$argentSession + $console->getTarif15min();
            $derniereSession->setMontant($argentSession);
            $entityManager->persist($derniereSession);
            $entityManager->flush();
        }
        return $this->redirectToRoute('sessions', [], Response::HTTP_SEE_OTHER);
    }
}

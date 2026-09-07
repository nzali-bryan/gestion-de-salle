<?php

namespace App\Controller;

use App\Entity\Console;
use App\Form\ConsoleType;
use App\Repository\ConsoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/console')]
final class ConsoleController extends AbstractController
{
    #[Route(name: 'app_console_index', methods: ['GET'])]
    public function index(ConsoleRepository $consoleRepository): Response
    {
        return $this->render('console/index.html.twig', [
            'consoles' => $consoleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_console_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $console = new Console();
        $form = $this->createForm(ConsoleType::class, $console);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $console->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($console);
            $entityManager->flush();
            

            return $this->redirectToRoute('app_console_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('console/new.html.twig', [
            'console' => $console,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_console_show', methods: ['GET'])]
    public function show(Console $console): Response
    {
        return $this->render('console/show.html.twig', [
            'console' => $console,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_console_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Console $console, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsoleType::class, $console);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_console_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('console/edit.html.twig', [
            'console' => $console,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_console_delete', methods: ['POST'])]
    public function delete(Request $request, Console $console, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$console->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($console);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_console_index', [], Response::HTTP_SEE_OTHER);
    }
}

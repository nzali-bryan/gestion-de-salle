<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription_user')]
    public function index(HttpFoundationRequest $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $utilisateur= new Utilisateur();
        $form=$this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $utilisateur,
                $utilisateur->getPassword()
            );
            $utilisateur->setPassword($hashedPassword);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/index.html.twig', [
            'form' => $form,
            'utilisateur'=>$utilisateur
        ]);
    }
}

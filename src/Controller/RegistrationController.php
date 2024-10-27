<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ProprietaireSalle;
use App\Form\ClientRegistrationType;
use App\Form\ProprietaireSalleRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function registerClient(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientRegistrationType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $password = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($client, $password);
            $client->setPassword($hashedPassword);

            // Save the client to the database
            $entityManager->persist($client);
            $entityManager->flush();

            // Optionally, add a flash message and redirect
            $this->addFlash('success', 'Registration successful! You can now log in.');
            return $this->redirectToRoute('app_home'); // Redirect to the login page or another appropriate route
        }

        return $this->render('registration/client_register.html.twig', [
            'ClientRegistration' => $form->createView(),
        ]);
    }

    #[Route('/register/proprietaire', name: 'app_register_proprietaire')]
    public function registerProprietaire(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $proprietaire = new ProprietaireSalle();
        $form = $this->createForm(ProprietaireSalleRegistrationType::class, $proprietaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $password = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($proprietaire, $password);
            $proprietaire->setPassword($hashedPassword);

            // Save the proprietaire to the database
            $entityManager->persist($proprietaire);
            $entityManager->flush();

            // Optionally, add a flash message and redirect
            $this->addFlash('success', 'Registration successful! You can now log in.');
            return $this->redirectToRoute('app_home'); // Redirect to the login page or another appropriate route
        }

        return $this->render('registration/proprietaire_register.html.twig', [
            'ProprietaireSalleRegistration' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ProprietaireSalle;
use App\Entity\User;
use App\Form\ClientRegistrationType;
use App\Form\ProprietaireSalleRegistrationType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier)
    { }

    #[Route('/register', name: 'app_register')]
    public function registerClient(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
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

            // Generate a signed URL and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $client,
                (new TemplatedEmail())
                    ->from(new Address('kchaoumohamed344@gmail.com', 'MailVerification'))
                    ->to((string) $client->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Optionally, add a flash message and redirect
            $this->addFlash('success', 'Registration successful! Please check your email for verification.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/client_register.html.twig', [
            'ClientRegistration' => $form->createView(),
        ]);
    }

    #[Route('/register/proprietaire', name: 'app_register_proprietaire')]
    public function registerProprietaire(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
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

            // Generate a signed URL and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $proprietaire,
                (new TemplatedEmail())
                    ->from(new Address('kchaoumohamed344@gmail.com', 'MailVerification'))
                    ->to((string) $proprietaire->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Optionally, add a flash message and redirect
            $this->addFlash('success', 'Registration successful! Please check your email for verification.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/proprietaire_register.html.twig', [
            'ProprietaireSalleRegistration' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user) {
                throw new \Exception('No authenticated user found.');
            }

            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // Update the user's verified status in the database
        $user->setVerified(true);
        $entityManager->persist($user);
        $entityManager->flush();

        // Add success flash message and redirect
        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_home');
    }
}

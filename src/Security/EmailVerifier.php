<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        try {
            // Generate the signed URL and signature components
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                $verifyEmailRouteName,
                (string) $user->getId(),
                (string) $user->getEmail()
            );

            // Debugging: log signature components
            error_log('Signature Components: ' . print_r($signatureComponents, true));

            // Get the email context or initialize it if it is null
            $context = $email->getContext();
            if (null === $context) {
                $context = []; // Initialize context if it's null
            }

            // Debugging: log context before setting it
            error_log('Email Context Before: ' . print_r($context, true));

            // Check if the signature URL is properly generated
            $signedUrl = $signatureComponents->getSignedUrl();
            if (!$signedUrl) {
                throw new \RuntimeException('Failed to generate a valid signed URL for email confirmation.');
            }

            // Set the required parameters in the context
            $context['signedUrl'] = $signedUrl;
            $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
            $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

            // Debugging: log context after setting values
            error_log('Email Context After: ' . print_r($context, true));

            // Apply the context to the email
            $email->context($context);

            // Try sending the email
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log any exceptions and rethrow them for further inspection
            error_log('Error sending email: ' . $e->getMessage());
            throw new \RuntimeException('Error sending email: ' . $e->getMessage());
        } catch (TransportExceptionInterface $e) {
            // Log the transport exception for email
            error_log('Email transport error: ' . $e->getMessage());
            throw new \RuntimeException('Email transport error: ' . $e->getMessage());
        }
    }
}

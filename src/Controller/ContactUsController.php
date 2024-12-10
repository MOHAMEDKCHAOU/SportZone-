<?php
namespace App\Controller;

use App\Entity\ContactUs;
use App\Form\ContactUsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    #[Route('/contact', name: 'contact_us')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
{
$contactUs = new ContactUs();
$form = $this->createForm(ContactUsType::class, $contactUs);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$entityManager->persist($contactUs);
$entityManager->flush();

$this->addFlash('success', 'Your message has been sent successfully!');

return $this->redirectToRoute('contact_us');
}

return $this->render('contact_us/index.html.twig', [
'form' => $form->createView(),
]);
}
}

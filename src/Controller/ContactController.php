<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $contact = new Contact();
        
        // Pré-remplir l'email si l'utilisateur est connecté
        if ($this->getUser()) {
            $contact->setEmail($this->getUser()->getEmail());
            $contact->setFullName($this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName());
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoyer un email de confirmation
            $email = (new Email())
                ->from('noreply@exercices.com')
                ->to($contact->getEmail())
                ->subject('Confirmation de votre message')
                ->html($this->renderView('emails/contact_confirmation.html.twig', [
                    'contact' => $contact
                ]));

            $mailer->send($email);

            // Envoyer un email à l'administrateur
            $adminEmail = (new Email())
                ->from('noreply@exercices.com')
                ->to('admin@exercices.com')
                ->subject('Nouveau message de contact')
                ->html($this->renderView('emails/contact_notification.html.twig', [
                    'contact' => $contact
                ]));

            $mailer->send($adminEmail);

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
} 
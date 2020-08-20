<?php

namespace App\Controller;

use App\Form\ContactType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if($this->isGranted('ROLE_ADMIN') && $form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Merci d\'avoir pris contact');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact/index.html.twig', ['contactForm' => $form->createView() ]);
    }

}

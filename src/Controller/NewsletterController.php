<?php

namespace App\Controller;

use App\Entity\NewsletterEmail;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'app_newsletter_subscribe', methods: ['GET', 'POST'])]
    public function subscribe(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $newsletterEmail = new NewsletterEmail();
        $form = $this->createForm(NewsletterType::class, $newsletterEmail);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsletterEmail->setSubscribed(true);
            $newsletterEmail->setSubscriptionDate(new \DateTime());
            $em->persist($newsletterEmail);
            $em->flush();

            return $this->redirectToRoute('app_newsletter_subscribe_confirm');
        }

        return $this->render('newsletter/subscribe.html.twig', [
            'newsletter_form' => $form,
        ]);
    }

    #[Route('/newsletter/subscribe/confirm', name: 'app_newsletter_subscribe_confirm')]
    public function subscribeConfirm(): Response
    {
        return $this->render('newsletter/subscribe_confirm.html.twig');
    }
}

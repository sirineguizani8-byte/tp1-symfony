<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ArticlesController extends AbstractController
{
    #[Route('/articles/nouveau', name: 'app_article_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        // Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);

        // Traitement de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            // Message flash de confirmation
            $this->addFlash('success', 'Article créé avec succès !');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupérer les 3 derniers articles publiés
        $derniersArticles = $articleRepository->findLastPublished(3);

        return $this->render('articles/index.html.twig', [
            'articles' => $derniersArticles,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_detail', requirements: ['id' => '\d+'])]
    public function detail(Article $article): Response
    {
        return $this->render('articles/detail.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/modifier', name: 'app_article_modifier', requirements: ['id' => '\d+'])]
    public function modifier(
        Article $article,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Article modifié avec succès !');

            return $this->redirectToRoute('app_article_detail', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('articles/modifier.html.twig', [
            'formulaire' => $form,
            'article' => $article,
        ]);
    }

    #[Route(
        '/articles/{id}/supprimer',
        name: 'app_article_supprimer',
        requirements: ['id' => '\d+'],
        methods: ['POST']
    )]
    public function supprimer(
        Article $article,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Vérification du token CSRF pour la sécurité
        if ($this->isCsrfTokenValid(
            'supprimer_' . $article->getId(),
            $request->request->get('_token')
        )) {
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'Article supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide. Suppression annulée.');
        }

        return $this->redirectToRoute('app_articles');
    }

    // Envoi d'un email avec Symfony Mailer
    #[Route('/articles/test-email', name: 'app_article_test_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Nouvel Article !')
            ->text('Un nouvel article a été publié sur le blog.')
            ->html('<p>Un nouvel article a été publié sur le blog.</p>');

        // Envoyer l'email
        $mailer->send($email);

        // Message flash
        $this->addFlash('success', 'Email envoyé avec succès !');

        return $this->redirectToRoute('app_articles');
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(RequestStack $requestStack): Response
    {
        // Accéder à la session
        $session = $requestStack->getSession();

        // Récupérer le nombre de visites
        $nbVisites = $session->get('nb_visites', 0);

        // Incrémenter le nombre de visites
        $session->set('nb_visites', $nbVisites + 1);

        // Afficher le résultat dans Twig
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'nb_visites' => $nbVisites + 1,
        ]);
    }

    #[Route('/bonjour/{prenom}', name: 'app_bonjour')]
    public function bonjour(string $prenom): Response
    {
        return new Response("<h1>Bonjour $prenom ! Bienvenue sur Symfony 7.4</h1>");
    }

    #[Route('/profil/{id}', name: 'app_profil', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function profil(int $id): Response
    {
        return new Response("<h1>Profil de l'utilisateur n°$id</h1>");
    }
}
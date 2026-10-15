<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TacheController extends AbstractController
{
    // Route 1 : afficher toutes les tâches
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $tacheRepository): Response
    {
        $taches = $tacheRepository->findAll();

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    // Route 2 : ajouter une tâche en base de données
    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();

        $tache->setTitre('Terminer le TP Symfony');
        $tache->setDescription(
            'Créer un contrôleur, utiliser Twig et gérer les tâches avec Doctrine.'
        );
        $tache->setTerminee(false);
        $tache->setDateCreation(new \DateTime());

        $em->persist($tache);
        $em->flush();

        return $this->redirectToRoute('app_tache_detail', [
            'id' => $tache->getId(),
        ]);
    }

    // Route 3 : afficher une tâche par son ID
    #[Route(
        '/taches/{id}',
        name: 'app_tache_detail',
        requirements: ['id' => '\d+']
    )]
    public function detail(Tache $tache): Response
    {
        return $this->render('tache/detail.html.twig', [
            'tache' => $tache,
        ]);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;

class ResultatsController extends AbstractController
{
    #[Route('/resultats/{team}', name: 'app_resultats')]
    public function index(ChallengeRepository $challengeRepository, $team='Pas de team'): Response
    {
        $challenges= $challengeRepository->findAll();
        return $this->render('resultats/index.html.twig', [
            'challenges' => $challenges,
            'team' => $team,
        ]);
    }
}

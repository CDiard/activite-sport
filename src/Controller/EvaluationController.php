<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use App\Entity\Team;
use App\Repository\TeamRepository;

class EvaluationController extends AbstractController
{
    #[Route('/prof/evaluation/{challenge}/{team}', name: 'app_evaluation')]
    public function index(ChallengeRepository $challengeRepository, TeamRepository $TeamRepository, $team='Pas de team', $challenge='Frisbowl'): Response
    {
        return $this->render('evaluation_individuelle/index.html.twig', [
            'controller_name' => 'EvaluationController',
            'challenge' =>$challenge,
            'team' => $team,
        ]);
    }

    #[Route('/prof/evaluation-equipe/{team}', name: 'app_evaluation_equipe')]
    public function indexEquipe(TeamRepository $TeamRepository, $team='Les canards', $challenge='Frisbowl'): Response
    {
        return $this->render('evaluation_equipes/index.html.twig', [
            'controller_name' => 'EvaluationController',
            'challenge' =>$challenge,
            'team' => $team,
        ]);
    }
}
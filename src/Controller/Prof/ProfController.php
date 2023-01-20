<?php

namespace App\Controller\Prof;

use App\Repository\PlayerRepository;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfController extends AbstractController
{
    #[Route('/prof', name: 'app_prof_seance')]
    public function profSeance(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $menu = [
            [
                'pictogram' => 'picto_seance1.svg',
                'title' => 'Équipes',
                'description' => 'Ajouter ou supprimer des équipes et appliquer un coeff surprise',
                'link' => 'app_prof_teams',
            ],
            [
                'pictogram' => 'picto_seance2.svg',
                'title' => 'Joueurs',
                'description' => 'Supprimer des joueurs ou changer leurs équipes',
                'link' => 'app_prof_seance',//_players
            ],
            [
                'pictogram' => 'picto_seance3.svg',
                'title' => 'Épreuves',
                'description' => 'Ajouter ou supprimer des épreuves définir leur mode de fonctionnement',
                'link' => 'app_prof_seance',//_challenges
            ],
            [
                'pictogram' => 'picto_seance4.svg',
                'title' => 'Statistiques',
                'description' => 'Voir les statistiques',
                'link' => 'app_prof_seance',//_statistics
            ],
            [
                'pictogram' => 'picto_seance5.svg',
                'title' => 'Résultats',
                'description' => 'Accéder aux résultats',
                'link' => 'app_results',
            ],
        ];

        return $this->render('prof/seance.html.twig', [
            'menu' => $menu,
        ]);
    }

    #[Route('/prof/equipes', name: 'app_prof_teams')]
    public function profTeams(TeamRepository $teamRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $teams = $teamRepository->findAll();

        return $this->render('prof/teams.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/prof/equipe/supprimer/{id}', name: 'app_prof_teams_delete')]
    public function profTeamsDelete(int $id, EntityManagerInterface $entityManager, TeamRepository $teamRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $team = $teamRepository->find($id);

        $entityManager->remove($team);

        $entityManager->flush();

        return $this->redirectToRoute('app_prof_teams');
    }

    #[Route('/prof/reinitialiser', name: 'app_prof_reset')]
    public function profReset(EntityManagerInterface $entityManager, ResultRepository $resultRepository, PlayerRepository $playerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $results = $resultRepository->findAll();

        foreach ($results as $result) {
            $entityManager->remove($result);
        }

        $players = $playerRepository->findAll();

        foreach ($players as $player) {
            $entityManager->remove($player);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_prof_seance');
    }
}
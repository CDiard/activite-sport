<?php

namespace App\Controller\Prof;

use App\Form\ChooseTeamType;
use App\Repository\ChallengeRepository;
use App\Repository\PlayerRepository;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
                'link' => 'app_prof_players',
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
                'link' => 'app_prof_statistics',
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

    #[Route('/prof/joueurs', name: 'app_prof_players')]
    public function profPlayers(PlayerRepository $playerRepository, TeamRepository $teamRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $players = $playerRepository->findAll();
        $teams = $teamRepository->findAll();

        return $this->render('prof/players.html.twig', [
            'players' => $players,
            'teams' => $teams,
        ]);
    }

    #[Route('/prof/joueurs/modification', name: 'app_prof_players_modify')]
    public function profPlayersModify(Request $request, PlayerRepository $playerRepository, TeamRepository $teamRepository, ManagerRegistry $doctrine): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager = $doctrine->getManager();
        $players = $playerRepository->findAll();

        $arrayTeamsId = [];
        foreach ($players as $player) {
            $arrayTeamsId[$player->getId()] = $request->request->get('playerTeam-'.$player->getId());
        }

        foreach ($arrayTeamsId as $idPlayer => $idTeam) {
            $player = $playerRepository->find($idPlayer);
            $team = $teamRepository->find($idTeam);

            $player->setTeam($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prof_players');
    }

    #[Route('/prof/joueur/supprimer/{id}', name: 'app_prof_players_delete')]
    public function profPlayersDelete(int $id, EntityManagerInterface $entityManager, PlayerRepository $playerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $player = $playerRepository->find($id);

        $entityManager->remove($player);

        $entityManager->flush();

        return $this->redirectToRoute('app_prof_players');
    }

    #[Route('/prof/statistique', name: 'app_prof_statistics')]
    public function profStatistics(ChallengeRepository $challengeRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $challenges = $challengeRepository->findAll();

        $arrayChallenges = [];
        foreach ($challenges as $challenge) {

            $arrayPoints = [];

            if ($challenge->getResults()->count() > 0) {
                $arrayChallenges[$challenge->getName()] = [
                    'results' => [],
                    'maxPoints' => 0,
                    'midPoints' => 0,
                ];

                foreach ($challenge->getResults() as $result) {
                    $arrayChallenges[$challenge->getName()]['results'][] = [
                        'teamName' => $result->getTeam()->getName(),
                        'teamPoints' => $result->getPointsEarned(),
                    ];

                    $arrayPoints[] = $result->getPointsEarned();
                }

                $arrayChallenges[$challenge->getName()]['maxPoints'] = max($arrayPoints); //Calcul maximum points sur l'épreuve
                $arrayChallenges[$challenge->getName()]['midPoints'] = max($arrayPoints) / 2; //Calcul median de points sur l'épreuve
            }

        }

        return $this->render('prof/statistics.html.twig', [
            'arrayChallenges' => $arrayChallenges,
        ]);
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

<?php

namespace App\Controller\Prof;

use App\Entity\Challenge;
use App\Entity\Result;
use App\Entity\TempTeam;
use App\Form\ChallengeType;
use App\Form\ResultType;
use App\Repository\ChallengeRepository;
use App\Form\TeamsType;
use App\Repository\PlayerRepository;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use App\Repository\TempTeamRepository;
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
                'link' => 'app_prof_challenges',
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
    public function profTeams(TeamRepository $teamRepository, TempTeamRepository $tempTeamRepository, ManagerRegistry $doctrine, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $em = $doctrine->getManager();
        
        $tempTeam = new TempTeam();

        $oldTeams = $teamRepository->findAll();

        foreach ($oldTeams as $oldTeam) {
            $tempTeam->addTeam($oldTeam);
        }

        $form = $this->createForm(TeamsType::class, $tempTeam);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tempTeam = $form->getData();
            $em->persist($tempTeam);
            $em->flush();

            foreach($tempTeamRepository->findByNotId($tempTeam->getId()) as $oldTempTeam){
                $em->remove($oldTempTeam);
            }

            foreach($teamRepository->findByName("") as $emptyTeam) {
                $em->remove($emptyTeam);
            }

            $em->flush();
            return $this->redirectToRoute("app_prof_teams");
        }

        return $this->render('prof/teams.html.twig', [
            'form' => $form->createView(),
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
            $arrayTeamsId[$player->getId()] = $request->request->get('playerTeam-' . $player->getId());
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

    #[Route('/prof/epreuves', name: 'app_prof_challenges')]
    public function profChallenges(ChallengeRepository $challengeRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $challenges = $challengeRepository->findAll();

        return $this->render('prof/challenges.html.twig', [
            'challenges' => $challenges,
        ]);
    }

    #[Route('/prof/epreuves/detail/{id}', name: 'app_prof_challenges_single')]
    public function profChallengesSingle(int $id, Request $request, EntityManagerInterface $entityManager, ChallengeRepository $challengeRepository, ResultRepository $resultRepository, TeamRepository $teamRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($id == 0) {
            $challenge = new Challenge();
            $form = $this->createForm(ChallengeType::class, $challenge);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($challenge);
                $entityManager->flush();
            }
        } elseif (!empty($id)) {
            $challenge = $challengeRepository->find($id);
            $form = $this->createForm(ChallengeType::class, $challenge);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
            }

            // Select teams not in this challenge
            $resultsChallenge = $resultRepository->findBy(['challenge' => $challenge]);
            $teams = $teamRepository->findAll();

            $resultsInArray = [];
            foreach ($resultsChallenge as $resultChallenge) {
                $resultsInArray[] = $resultChallenge->getTeam()->getId();
            }

            $teamsChallenge = [];
            foreach ($teams as $team) {
                if (!in_array($team->getId(), $resultsInArray)) {
                    $teamsChallenge[$team->getId()] = $team->getName();
                }
            }
        }

        return $this->render('prof/challenges_single.html.twig', [
            'challenge' => $challenge,
            'challengeForm' => $form->createView(),
            'teamsChallenge' => $teamsChallenge,
        ]);
    }

    #[Route('/prof/epreuves/evaluation/{idChallenge}', name: 'app_prof_challenges_evaluate')]
    public function profChallengesEvaluate(int $idChallenge, Request $request, EntityManagerInterface $entityManager, ChallengeRepository $challengeRepository, TeamRepository $teamRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (empty($idChallenge) && empty($_GET['team'])) {
            return $this->redirectToRoute('app_prof_challenges');
        }

        $idTeam = $_GET['team'];

        $challenge = $challengeRepository->find($idChallenge);
        $team = $teamRepository->find($idTeam);

        $result = new Result();
        $form = $this->createForm(ResultType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result->setChallenge($challenge);
            $result->setTeam($team);
            $result->setPointsEarned(0);

            if ($challenge->isType() == 'temps') {
                $result->setScore(null);
            } elseif ($challenge->isType() == 'score') {
                $result->setTime(null);
            }

            $team->setCoeff($_POST['coeff']);

            $entityManager->persist($result);
            $entityManager->flush();

            return $this->redirectToRoute('app_prof_challenges_single', [
                'id' => $challenge->getId(),
            ]);
        }

        return $this->render('prof/challenges_evaluate.html.twig', [
            'resultForm' => $form->createView(),
            'challenge' => $challenge,
            'team' => $team,
        ]);
    }

    #[Route('/prof/epreuves/supprimer/{id}', name: 'app_prof_challenges_delete')]
    public function profChallengesDelete(int $id, ChallengeRepository $challengeRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $challenge = $challengeRepository->find($id);

        $entityManager->remove($challenge);

        $entityManager->flush();

        return $this->redirectToRoute('app_prof_challenges');
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

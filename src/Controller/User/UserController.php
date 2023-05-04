<?php

namespace App\Controller\User;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\ChooseNameType;
use App\Form\ChooseTeamType;
use App\Repository\ChallengeRepository;
use App\Repository\PlayerRepository;
use App\Repository\ResultRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_user')]
    public function index(PlayerRepository $playerRepository, ChallengeRepository $challengeRepository, ResultRepository $resultRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId) {
            return $this->redirectToRoute('app_user_name');
        }

        $player = $playerRepository->find($playerId);

        if ($player == null) {
            return $this->redirectToRoute('app_user_name');
        }

        $challenges = $challengeRepository->findAll();

        //Calcul du score
        $scoreTeam = 0;
        $results = $resultRepository->findBy(
            ['team' => $player->getTeam()]
        );

        foreach ($results as $result) {
            $scoreTeam = $scoreTeam + $result->getPointsEarned();
        }

        return $this->render('user/home.html.twig', [
            'player' => $player,
            'challenges' => $challenges,
            'scoreTeam' => $scoreTeam,
            'afficheDeco' => true,
        ]);
    }

    #[Route('/nom', name: 'app_user_name')]
    public function chooseName(Request $request, EntityManagerInterface $entityManager, PlayerRepository $playerRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if ($playerId) {
            $player = $playerRepository->find($playerId);

            if ($player !== null) {
                return $this->redirectToRoute('app_user');
            }

            $session->remove('playerId');
        }

        $player = new Player();
        $form = $this->createForm(ChooseNameType::class, $player);
        $form->handleRequest($request);

        $verifPlayer = $playerRepository->findOneBy(['username' => $player->getUsername()]);

        if (($verifPlayer == null) && ($form->isSubmitted() && $form->isValid())) {
            $entityManager->persist($player);
            $entityManager->flush();

            $verifPlayer2 = $playerRepository->findOneBy(['username' => $player->getUsername()]);

            $playerId = $verifPlayer2->getId();
            $playerUsername = $verifPlayer2->getUsername();

            $session->set('playerId', $playerId);
            $session->set('playerUsername', $playerUsername);

            return $this->redirectToRoute('app_user_team');
        } elseif ($verifPlayer !== null) {
            $playerId = $verifPlayer->getId();
            $playerUsername = $verifPlayer->getUsername();

            $session->set('playerId', $playerId);
            $session->set('playerUsername', $playerUsername);

            return $this->redirectToRoute('app_user_team');
        }

        return $this->render('user/chooseName.html.twig', [
            'chooseNameForm' => $form->createView(),
        ]);
    }

    #[Route('/joueur', name: 'app_user_team')]
    public function chooseTeam(Request $request, PlayerRepository $playerRepository, ManagerRegistry $doctrine): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId) {
            return $this->redirectToRoute('app_user_name');
        }

        $player = $playerRepository->find($playerId);

        if ($player == null) {
            return $this->redirectToRoute('app_user_name');
        }

        $players = $playerRepository->findAll();

        $entityManager = $doctrine->getManager();
        $playerTeam = $entityManager->getRepository(Player::class)->find($playerId);
        $form = $this->createForm(ChooseTeamType::class, $playerTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        $playerId = $session->get('playerId');
        $playerUsername = $session->get('playerUsername');

        return $this->render('user/chooseTeam.html.Twig', [
            'chooseTeamForm' => $form->createView(),
            'allPlayers' => $players,
            'playerId' => $playerId,
            'playerUsername' => $playerUsername,
        ]);
    }

    #[Route('/resultats', name: 'app_results')]
    public function results(TeamRepository $teamRepository, PlayerRepository $playerRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId && !$this->getUser()) {
            return $this->redirectToRoute('app_user_name');
        }

        $teams = $teamRepository->findAll();

        $arrayResults = [];
        foreach ($teams  as $key => $team) {
            $pointsTeam = 0;
            foreach ($team->getResults() as $result) {
                $pointsTeam = $pointsTeam + $result->getPointsEarned();
            }

            $arrayResults[$pointsTeam] = [
                'name' => $team->getName(),
                'points' => $pointsTeam,
            ];
        }

        krsort($arrayResults);

        return $this->render('user/results.html.Twig', [
            'results' => $arrayResults,
        ]);
    }

    #[Route('/resultats/epreuves', name: 'app_results_challenge_list')]
    public function resultsChallengeList(PlayerRepository $playerRepository, ChallengeRepository $challengeRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId && !$this->getUser()) {
            return $this->redirectToRoute('app_user_name');
        }

        $challenges = $challengeRepository->findAll();

        return $this->render('user/results-challenge-list.html.Twig', [
            'challenges' => $challenges,
        ]);
    }

    #[Route('/resultats/epreuves/{id}', name: 'app_results_challenge_single')]
    public function resultsChallengeSingle(int $id, PlayerRepository $playerRepository, ChallengeRepository $challengeRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId && !$this->getUser()) {
            return $this->redirectToRoute('app_user_name');
        }

        $challenge = $challengeRepository->find($id);

        return $this->render('user/results-challenge-single.html.Twig', [
            'challenge' => $challenge,
        ]);
    }

    #[Route('/deconnexion', name: 'app_user_deconnexion')]
    public function deconnexionPlayer(): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('playerId');

        return $this->redirectToRoute('app_user_name');
    }

    #[Route('/equipe/challenges/{id}', name: 'app_team_challenges')]
    public function playerTeamChallengesLeft(Team $team, ChallengeRepository $challengeRepository, ResultRepository $resultRepository): JsonResponse
    {
        $challenges = $challengeRepository->findAll();

        $response = [];

        foreach($challenges as $challenge) {
            if ($resultRepository->findOneBy(['team' => $team->getId(), 'challenge' => $challenge->getId()]) == null) {

                $response[] = [
                    'name' => $challenge->getName(), 
                    'description' => $challenge->getDescription()
                ];
            }
        }

        return $this->json($response);
    }

    #[Route('/equipe/points/{id}', name: 'app_team_points')]
    public function playerTeamPoints(Team $team, ResultRepository $resultRepository): JsonResponse
    {
        //Calcul du score
        $scoreTeam = 0;
        $results = $resultRepository->findBy(
            ['team' => $team->getId()]
        );

        foreach ($results as $result) {
            $scoreTeam = $scoreTeam + $result->getPointsEarned();
        }

        return $this->json($scoreTeam);
    }
}

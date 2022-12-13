<?php

namespace App\Controller\User;

use App\Entity\Player;
use App\Entity\User;
use App\Form\ChooseNameType;
use App\Form\ChooseTeamType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId) {
            return $this->redirectToRoute('app_user_name');
        }
        return $this->render('user/home.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/nom', name: 'app_user_name')]
    public function chooseName(Request $request, EntityManagerInterface $entityManager, PlayerRepository $playerRepository): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if ($playerId) {
            return $this->redirectToRoute('app_user');
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

    #[Route('/equipe', name: 'app_user_team')]
    public function chooseTeam(Request $request, TeamRepository $teamRepository, ManagerRegistry $doctrine): Response
    {
        $session = $this->requestStack->getSession();
        $playerId = $session->get('playerId');

        if (!$playerId) {
            return $this->redirectToRoute('app_user_name');
        }

        $teams = $teamRepository->findAll();

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
            'teams' => $teams,
            'playerId' => $playerId,
            'playerUsername' => $playerUsername,
        ]);
    }

    #[Route('/deconnexion', name: 'app_user_deconnexion')]
    public function deconnexionPlayer(): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('playerId');

        return $this->redirectToRoute('app_user_name');
    }
}

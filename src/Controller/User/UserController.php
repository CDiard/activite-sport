<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_user_name');
        }
        return $this->render('user/home.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/nom', name: 'app_user_name')]
    public function chooseName(): Response
    {
        
        return $this->render('user/chooseName.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/equipe', name: 'app_user_team')]
    public function chooseTeam(): Response
    {
        return $this->render('user/chooseTeam.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}

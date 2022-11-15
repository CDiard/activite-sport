<?php

namespace App\Controller\Admin;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdministrationController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //


        //$adminUrlGenerator = $this->container->get(Joueur::class);
        //$url = $adminUrlGenerator->setController(JoueurCrudController::class)->generateUrl();
        //return $this->redirect($adminUrlGenerator->setController(JoueurCrudController::class)->generateUrl());
        //return $this->redirect($url);

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
         return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Panneau de bord');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Gestion des équipes', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour à l\'application', '', 'app_accueil');
       yield MenuItem::linkToCrud('Joueurs', '', Joueur::class);
        yield MenuItem::linkToCrud('Equipes', '', Equipe::class);
        yield MenuItem::linkToCrud('Admins','',User::class);
    }
}

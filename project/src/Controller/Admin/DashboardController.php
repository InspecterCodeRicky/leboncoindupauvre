<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/mon-profil", name="show_profile")
     */
    public function showProfile(): Response
    {
        return $this->render('admin/profile.html.twig');
    }

    /**
     * @Route("/admin/modifier-profile", name="modify_profile")
     */
    public function modifyProfile(): Response
    {
        return $this->render('admin/reset-password.html.twig');
    }

    /**
     * @Route("/admin/reset-password", name="reset_password")
     */
    public function resetPassword(): Response
    {
        return $this->render('admin/profile.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le bon coin du pauvre');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Annonces', 'fas fa-list', Annonce::class);
        yield MenuItem::linkToRoute('Profil', 'fas fa-user', 'show_profile');
    }
}

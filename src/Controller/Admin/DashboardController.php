<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Notice;
use App\Entity\Subscription;
use App\Entity\User;
use App\Form\AdminType;
use App\Repository\SubscriptionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/textes", name="admin_textes")
     */
    public function text(Request $request): Response
    {
        $form = $this->createForm(AdminType::class);
        $form->handleRequest($request);

        // TODO : Traitement à faire lorsque l'on enregistre le texte

        return $this->render('admin/text.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img class="h-50 my-4" src="build/images/content/logo.8c64e83b.png">');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord');
        yield MenuItem::linkToCrud('Utilisateurs', '', User::class);
        yield MenuItem::linkToCrud('Formule', '', Subscription::class);
        yield MenuItem::linkToCrud('Avis', '', Notice::class);
        yield MenuItem::linkToCrud('Evenements', '', Event::class);
        yield MenuItem::linkToRoute('Textes', null, 'admin_textes', []);
    }
}

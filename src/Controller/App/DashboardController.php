<?php

namespace App\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="sd_admin_app_dashboard")
     */
    public function index(): Response
    {
        return $this->render('app/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}

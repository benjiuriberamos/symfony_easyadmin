<?php

namespace App\Controller\App;

use App\Controller\Core\BaseSimpleController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app-home")
 */
class AppHomeController extends BaseSimpleController
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @Route("/", name="sd_admin_app_home", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        return parent::init($request);
    }
}

<?php

namespace App\Controller\App;

use App\Controller\Core\BaseSimpleController;

use App\Entity\AppInfo;
use App\Form\AppInfoType;
use App\Manager\AppInfoManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app-info")
 */
class AppInfoController extends BaseSimpleController
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @Route("/", name="sd_admin_app_info", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        return parent::init($request);
    }
}

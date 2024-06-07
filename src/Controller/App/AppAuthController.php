<?php

namespace App\Controller\App;

use App\Controller\Core\BaseSimpleController;

use App\Entity\AppAuth;
use App\Form\AppAuthType;
use App\Manager\AppAuthManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app-auth")
 */
class AppAuthController extends BaseSimpleController
{
    /**
     * Constructor
     */
    public function __construct(AppAuthManager $manager)
    {
        $this->class = AppAuth::class;
        $this->entity = 'AppAuth';
        $this->formType = AppAuthType::class;
        $this->package = 'app';
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="sd_admin_app_auth", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        return parent::init($request);
    }
}

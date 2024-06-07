<?php

namespace App\Controller\Api;

use App\Entity\AppAuth;
use App\Entity\AppInfo;
use App\Helper\ApiResponse;
use App\Traits\ImageTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends BaseApiController
{
    use ImageTrait;

    #[Route('/onboarding', name: 'app_onboarding')]
    public function onboarding(): JsonResponse
    {

        $data = [
        ];

        return ApiResponse::success(null, $data);
    }

    #[Route('/home', name: 'app_home')]
    public function home(): JsonResponse
    {

        

        return ApiResponse::success(null, []);
    }

    #[Route('/avatar', name: 'app_avatar')]
    public function avatar(): JsonResponse
    {
        

        return ApiResponse::success(null, []);
    }

    #[Route('/legal', name: 'app_legal')]
    public function legal(): JsonResponse
    {

        $legal = [
            
        ];

        return ApiResponse::success(null, $legal);
    }

}

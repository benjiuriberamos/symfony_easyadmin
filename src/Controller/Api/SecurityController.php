<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Helper\ApiResponse;
use App\Helper\Str;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    
    #[Route('/login', name: 'api_login')]
    public function login(UserManager $manager): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            return ApiResponse::error('Credenciales inválidas', [], Response::HTTP_UNAUTHORIZED);
        }

        if (get_class($user) !== User::class) {
            return ApiResponse::error(ApiResponse::MESSAGE_ERROR, [], Response::HTTP_UNAUTHORIZED);
        }

        $token = base64_encode(Str::random(40));
        $user->setToken($token);
        $manager->save($user);

        return ApiResponse::success('Inicio de sesión exitoso', [
            'user' => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
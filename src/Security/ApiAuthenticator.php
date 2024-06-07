<?php

namespace App\Security;

use App\Helper\ApiResponse;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiAuthenticator extends AbstractAuthenticator
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function supports(Request $request): ?bool
    {
        return $this->hasBearerToken($request);
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $token = $this->bearerToken($request);
        if (is_null($token)) {
            throw new CustomUserMessageAuthenticationException('Token no proporcionado');
        }

        $user = $this->userManager->byToken($token);
        if (is_null($user)) {
            throw new CustomUserMessageAuthenticationException('Token inválido');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return ApiResponse::error($exception->getMessage(), [], Response::HTTP_UNAUTHORIZED);
    }

    protected function bearerToken(Request $request): ?string
    {
        if (!$this->hasBearerToken($request)) {
            return null;
        }

        $header = $request->headers->get('Authorization');
        $position = strpos($header, 'Bearer ');
        if ($position !== false) {
            $header = substr($header, $position + 7);
            return strpos($header, ',') !== false ? strstr($header, ',', true) : $header;
        }

        return null;
    }

    protected function hasBearerToken(Request $request): bool
    {
        if (is_null($header = $request->headers->get('Authorization'))) {
            return false;
        }

        if (strpos($header, 'Bearer ') === false) {
            return false;
        }

        return true;
    }
}
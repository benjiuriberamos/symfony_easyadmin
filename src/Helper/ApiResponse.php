<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    const MESSAGE_ERROR = 'Se produjo un error inesperado';

    protected const SUCCESS = 'success';
    protected const ERROR = 'error';

    public static function success(?string $message, ?array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return (new ApiResponse)->sendResponse(self::SUCCESS, $message, $data, $code);
    }

    public static function error(?string $message, ?array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return (new ApiResponse)->sendResponse(self::ERROR, $message, $data, $code);
    }

    public static function unexpectedError(): JsonResponse
    {
        return (new ApiResponse)->error(self::MESSAGE_ERROR);
    }

    public static function unauthenticated(): JsonResponse
    {
        return (new ApiResponse)->sendResponse(self::ERROR, 'Unauthenticated.', [], Response::HTTP_UNAUTHORIZED);
    }

    protected function sendResponse(string $status, ?string $message, ?array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'status' => $status,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data) {
            $response['data'] = $data;
        }

        return new JsonResponse($response, $code);
    }
}

<?php

namespace App\Service;

use Illuminate\Http\JsonResponse;

abstract class ResponseErrorService
{
    public static function json(string $message, int $code): JsonResponse
    {
        return new JsonResponse(
            [
                'error' => true,
                'code' => $code,
                'message' => $message
            ],
            $code
        );
    }
}

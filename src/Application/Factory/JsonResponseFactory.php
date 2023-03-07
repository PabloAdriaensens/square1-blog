<?php

namespace App\Application\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseFactory
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    public function success(array $data = []): JsonResponse
    {
        $response_object = [
            'success' => true,
        ];

        if ($data) {
            $response_object['data'] = $data;
        }

        return new JsonResponse(
            $response_object
        );
    }

    /**
     * @param string $message
     * @param int $error_code
     * @return JsonResponse
     */
    public function error(string $message = 'Unexpected internal server error.', int $error_code = 500): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => false,
                'message' => $message,
            ],
            $error_code
        );
    }
}

<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class UserJWT
{
    /**
     * Get the token array structure.
     *
     * @param  string $token
     */
    private function createNewToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 4
        ]);
    }

    public function getTokenJWT(array $data)
    {
        if (!$token = auth('api')->attempt($data)) {
            return false;
        }

        return $this->createNewToken($token);
    }

    /**
     * Auth for the Swagger
     *
     * @return JsonResponse
     */
    public function authenticate(string $headerAuthorization)
    {
        $rawHeader = explode(' ', $headerAuthorization);

        if (count($rawHeader) !== 2) {
            return response()->json([
                'errors' => [
                    'authorization' => ['Invalid authorization header.']
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $authData = explode(':', base64_decode($rawHeader[1]));

        $data = [
            'email' => $authData[0],
            'password' => $authData[1]
        ];

        return $this->getTokenJWT($data);
    }
}

<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;


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
        try {
            if (!$token = auth('api')->attempt($data)) {
                return false;
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
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

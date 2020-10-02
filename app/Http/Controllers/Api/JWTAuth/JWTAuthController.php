<?php

namespace App\Http\Controllers\Api\JWTAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class JWTAuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $userCreate = new User();
        $userCreate->name = $request->name;
        $userCreate->email = $request->email;
        $userCreate->password = Hash::make($request->password);
        $userCreate->save();

        return response()->json([
            "message" => "User successfully registered",
            "user" => $userCreate
        ], Response::HTTP_CREATED);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        return $this->authenticate($request->post());
    }

    private function authenticate($data)
    {
        if (!$token = auth('api')->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 4
        ]);
    }

    /**
     * Auth for the Swagger
     *
     * @return JsonResponse
     */
    public function auth(Request $request)
    {
        $rawHeader = explode(' ', $request->header('Authorization'));

        if (count($rawHeader) !== 2) {
            return response()->json([
                'errors' => [
                    'authorization' => ['Invalid authorization header.']
                ]
            ], 422);
        }

        $authData = explode(':', base64_decode($rawHeader[1]));

        $data = [
            'email' => $authData[0],
            'password' => $authData[1]
        ];

        return $this->authenticate($data);
    }
}

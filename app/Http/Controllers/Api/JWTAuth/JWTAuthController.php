<?php

namespace App\Http\Controllers\Api\JWTAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class JWTAuthController extends Controller
{
    private $userModel;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userModel = $userRepository;
    }


    /**
     * Cadastro de usuário no sistema
     *
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="storeUser",
     *      tags={"Usuario"},
     *      summary="Cadastro de usuarios",
     *      description="Cadastro de usuarios",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="Moises Rodrigues"),
     *              @OA\Property(property="email", type="string", example="moises.rodrigues@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Executado com sucesso",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "message": "User successfully registered",
     *                          "user": {
     *                              "name": "Moises Rodrigues",
     *                              "email": "moises.rodrigues@email.com",
     *                              "updated_at": "2020-10-03T02:00:14.000000Z",
     *                              "created_at": "2020-10-03T02:00:14.000000Z",
     *                              "id": 1
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "message": "The given data was invalid.",
     *                          "errors": {
     *                              "name": {
     *                                  "The name field is required."
     *                              },
     *                              "name": {
     *                                  "The name must be between 2 and 100 characters."
     *                              },
     *                              "email": {
     *                                  "The email has already been taken."
     *                              },
     *                              "password": {
     *                                  "The password field is required."
     *                              },
     *                              "password": {
     *                                  "The password must be at least 6 characters."
     *                              }
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function register(RegisterUserRequest $request)
    {
        $userCreated = $this->userModel->createNewUser($request->post());

        return response()->json([
            "message" => "User successfully registered",
            "user" => $userCreated
        ], Response::HTTP_CREATED);
    }


    /**
     * Login do usuário no sistema
     *
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="loginUser",
     *      tags={"Usuario"},
     *      summary="Login do usuario",
     *      description="Login do usuario no sistema",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="moises.rodrigues@email.com"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Executado com sucesso",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYwMTY4OTQ3NiwiZXhwIjoxNjAxNjkzMDc2LCJuYmYiOjE2MDE2ODk0NzYsImp0aSI6IlkwWEZNbUpocWZESlFlUEMiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EUfXxmxKkBeGtZdfainSTv-9b0UguAm4I-QYBPZ4TGU",
     *                          "token_type": "bearer",
     *                          "expires_in": 14400
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "error": "Unauthorized"
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
    public function login(LoginUserRequest $request)
    {
        $token = $this->userModel->login($request->post());

        if (!$token) {
            return $this->UserUnauthorized();
        }

        return $token;
    }


    public function authSwagger(Request $request)
    {
        $response = $this->userModel->authUserSwagger($request->header('Authorization'));

        if (!$response) {
            return $this->UserUnauthorized();
        }

        return $response;
    }

    private function UserUnauthorized()
    {
        return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }
}

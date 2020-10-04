<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;
use App\Services\UserJWT as UserJTWService;

class UserRepository implements UserRepositoryInterface
{
    private $userModel;

    private $userJWTService;

    /**
     * @param UserModel $model
     */
    public function __construct(UserModel $model, UserJTWService $userJWT)
    {
        $this->userModel = $model;
        $this->userJWTService = $userJWT;
    }

    /**
     * Create a user.
     *
     * @param array $data
     * @return UserModel
     */
    public function createNewUser(array $data): UserModel
    {
        $this->userModel->name = $data['name'];
        $this->userModel->email = $data['email'];
        $this->userModel->password = Hash::make($data['password']);
        $this->userModel->save();

        return $this->userModel;
    }

    /**
     * Login a user
     *
     * @param array $credentials
     */
    public function login(array $credentials)
    {
        return $this->userJWTService->getTokenJWT($credentials);
    }

    /**
     * Auth user Swagger
     *
     * @param array $credentials
     */
    public function authUserSwagger(string $headerAuthorization)
    {
        return $this->userJWTService->authenticate($headerAuthorization);
    }
}

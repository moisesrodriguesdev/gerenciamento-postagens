<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function createNewUser(array $data);

    public function login(array $credentials);

    public function authUserSwagger(string $headerAuthorization);
}

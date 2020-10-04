<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_user_success()
    {
        factory(User::class)->create($this->data());

        $response = $this->postJson('/api/auth/login', [
            'email' => 'usuario.completo@gmail.com',
            'password' => '123456'
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(["access_token", "token_type", "expires_in"]);
    }

    public function test_login_user_unauthorized()
    {
        factory(User::class)->create($this->data());

        $response = $this->postJson('/api/auth/login', [
            'email' => 'usuario.completo@gmail.com',
            'password' => '1234567'
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(["error" => "Unauthorized"]);
    }

    /**
     * @dataProvider validation_scenarios_login_user
     */
    public function test_validate_fields_login_user($dados, $erro)
    {
        $response = $this->postJson("/api/auth/login", $dados);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment($erro);
    }

    public function validation_scenarios_login_user(): array
    {
        return [
            [
                ['email' => 0],
                ['errors' =>
                [
                    'email' => ['The email must be a valid email address.'],
                    'password' => ['The password field is required.']
                ]]
            ],
            [
                ['password' => 0],
                ['errors' =>
                [
                    'email' => ['The email field is required.'],
                    'password' => ['The password must be at least 6 characters.']
                ]]
            ]
        ];
    }

    private function data()
    {
        return [
            'name' => 'Nome do usuario completooooo',
            'email' => 'usuario.completo@gmail.com',
            'password' => '$2y$10$BsMoMXh6Kt1hLhmxQb3NnOjOU0/8oSwFyZysbWqVRdGPsuCV6Y9d6'
        ];
    }
}

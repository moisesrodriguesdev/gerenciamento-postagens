<?php

namespace Tests\Feature\Api\User;

use App\Models\User;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_success()
    {
        $form = $this->data();

        $response = $this->postJson("/api/auth/register", $form);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            "message" => "User successfully registered"
        ]);
    }

    public function test_duplicate_user_email()
    {
        $form = $this->data();

        factory(User::class)->create($form);

        $response = $this->postJson("/api/auth/register", $form);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => [
                    "The email has already been taken."
                ]
            ]
        ]);
    }

    /**
     * @dataProvider validation_scenarios_register_user
     */
    public function test_validate_fields_register_user($dados, $erro)
    {
        $response = $this->postJson("/api/auth/register", $dados);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment($erro);
    }

    /**
     * @return array
     */
    public function validation_scenarios_register_user(): array
    {
        return [
            [
                ['name' => 0],
                ['errors' =>
                [
                    'email' => ['The email field is required.'],
                    'name' => ['The name must be between 2 and 100 characters.'],
                    'password' => ['The password field is required.']
                ]]
            ],
            [
                ['email' => 1],
                ['errors' =>
                [
                    'email' => ['The email must be a valid email address.'],
                    'name' => ['The name field is required.'],
                    'password' => ['The password field is required.']
                ]]
            ]
        ];
    }

    private function data()
    {
        return [
            'name' => 'Nome do usuario completoasa',
            'email' => 'usuario.completo@gmail.com',
            'password' => '123456'
        ];
    }
}

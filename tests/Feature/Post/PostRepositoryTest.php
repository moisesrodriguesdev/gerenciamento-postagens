<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PostRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authenticated();
    }

    /**
     * @dataProvider cenariosValidacao
     */
    public function testValidaCampos($dados, $erro)
    {
        $response = $this->postJson("api/post", $dados);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment($erro);
    }

    /**
     * @return array
     */
    public function cenariosValidacao(): array
    {
        return [
            [
                ['title' => null],
                ['errors' =>
                [
                    'content' => ['The content field is required.'],
                    'tags' => ['The tags field is required.'],
                    'title' => ['The title field is required.']
                ]],
            ],
            [
                ['tags' => 0],
                ['errors' =>
                [
                    'content' => ['The content field is required.'],
                    'tags' => ['The tags must be an array.'],
                    'title' => ['The title field is required.']
                ]],
            ]
        ];
    }
}

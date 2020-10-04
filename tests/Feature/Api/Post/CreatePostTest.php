<?php

namespace Tests\Feature\Api\Post;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CreatePostTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authenticated();
    }

    public function test_post_success()
    {
        $response = $this->postJson("api/posts", $this->data());
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(["title", "content", "tags", "autor_id", "id"]);
    }

    public function test_duplicate_post()
    {
        $response = $this->postJson("api/posts", $this->data());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "title" => [
                    "The title has already been taken."
                ]
            ]
        ]);
    }

    /**
     * @dataProvider validation_scenarios_post
     */
    public function test_validate_fields_post($dados, $erro)
    {
        $response = $this->postJson("api/posts", $dados);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment($erro);
    }

    /**
     * @return array
     */
    public function validation_scenarios_post(): array
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

    public function data()
    {
        return [
            'title' => 'Titulo post',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has',
            'tags' => [
                'GO',
                'PHP',
                'SQL'
            ]
        ];
    }
}

<?php

namespace Tests\Feature\Api\Post;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePostTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->authenticated();
    }

    public function test_update_post_belongs_user()
    {
        $form = $this->data();

        $post = factory(Post::class)->create($form->create);

        $response = $this->putJson("api/post/{$post->id}", $form->update);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response->decodeResponseJson('title'), $form->update['title']);
    }

    public function test_update_post_not_belongs_user()
    {
        $form = $this->data();

        $user = factory(User::class)->create($form->user);
        $post = factory(Post::class)->create([
            'title' => 'Titulo post',
            'autor_id' => $user->id,
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has',
            'tags' =>  json_encode(['PHP', 'SQL', 'Node'])
        ]);

        $response = $this->putJson("api/post/{$post->id}", $form->update);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'This post does not belong to you'
        ]);
    }

    public function test_update_post_invalid()
    {
        $form = $this->data();

        $response = $this->putJson("api/post/2", $form->update);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "errors" => [
                "post_id" => [
                    "The selected post id is invalid."
                ]
            ]
        ]);
    }

    private function data()
    {
        return (object) [
            'create' => [
                'title' => 'Titulo post',
                'autor_id' => $this->currentUser->id,
                'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has',
                'tags' =>  json_encode(['PHP', 'SQL', 'Node'])
            ],
            'update' => [
                "title" => "Alterar Titulo do post 1"
            ],
            'user' => [
                'name' => 'Nome do usuario completo',
                'email' => 'usuario.completo@gmail.com',
                'password' => '$2y$10$BsMoMXh6Kt1hLhmxQb3NnOjOU0/8oSwFyZysbWqVRdGPsuCV6Y9d6'
            ]
        ];
    }
}

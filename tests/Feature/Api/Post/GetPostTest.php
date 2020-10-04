<?php

namespace Tests\Feature\Api\Post;

use App\Models\Post;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;


class GetPostTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->authenticated();
    }

    public function test_get_all_post_belongs_user()
    {
        $post = factory(Post::class)->create($this->data());

        $response = $this->getJson("api/post");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            "id" => $post->id,
            "tags" => ["Node", "PHP", "SQL"],
            "title" => "Titulo post"
        ]);
    }

    private function data()
    {
        return [
            'title' => 'Titulo post',
            'autor_id' => $this->currentUser->id,
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has',
            'tags' =>  json_encode(['PHP', 'SQL', 'Node'])
        ];
    }
}

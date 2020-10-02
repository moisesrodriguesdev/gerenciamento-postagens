<?php

namespace App\Repositories\Eloquent;

use App\Models\Post as PostModel;
use App\Http\Resources\Post as PostResource;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    protected $postModel;

    /**
     * @param PostModel $model
     */
    public function __construct(PostModel $model)
    {
        $this->postModel = $model;
    }

    /**
     * Get all post belongs to user.
     *
     * @param string $tag
     */
    public function allUserPost($tag)
    {
        $postQuery = $this->postModel->query();

        if (!empty($tag)) {
            $postQuery = $postQuery->WhereJsonContains('tags', $tag);
        }

        return PostResource::collection(
            $postQuery->where(['autor_id' => auth()->user()->id])->get()
        )->collection;
    }


    /**
     * Create a post.
     *
     * @param array $data
     * @return PostModel
     */
    public function createNewPost(array $data): PostModel
    {
        $this->postModel->title = $data['title'];
        $this->postModel->content = $data['content'];
        $this->postModel->tags = json_encode($data['tags']);
        $this->postModel->autor_id = auth()->user()->id;
        $this->postModel->save();

        return $this->postModel;
    }

    /**
     * Update a post.
     *
     * @param array $data
     * @param int $postId
     * @return PostModel
     */
    public function updatePostById(array $data, int $postId): PostModel
    {
        $post = $this->findPostById($postId);

        $post->update($data);

        return $post;
    }

    /**
     * Delete a post.
     *
     * @param $id
     * @return PostModel
     */
    public function deletePostById(int $postId): PostModel
    {
        $post = $this->findPostById($postId);
        $post->delete();

        return $post;
    }

    /**
     * Find a post by id.
     *
     * @param int $postId
     * @return PostModel
     */
    public function findPostById(int $postId): PostModel
    {
        return $this->postModel->find($postId);
    }
}

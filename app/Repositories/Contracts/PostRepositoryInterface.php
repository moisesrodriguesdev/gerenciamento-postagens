<?php

namespace App\Repositories\Contracts;

interface PostRepositoryInterface
{
    public function allUserPost($tag);

    public function createNewPost(array $data);

    public function updatePostById(array $data, int $postId);

    public function deletePostById(int $postId);

    public function findPostById(int $postId);
}

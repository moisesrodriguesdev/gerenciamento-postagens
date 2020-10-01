<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private $postModel;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postModel = $postRepository;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->postModel->allUserPost($request->query('tag')),
            Response::HTTP_OK
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts',
            'content' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $postCreated = $this->postModel->createNewPost($request->post());

        return response()->json($postCreated, Response::HTTP_CREATED);
    }

    public function update(Request $request, int $postId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'unique:posts',
            'tags' => 'array',
            'tags.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postModel->updatePostById($request->post(), $postId);

        return response()->json($post->getChanges(), Response::HTTP_OK);
    }

    public function delete(int $postId)
    {
        $this->postModel->deletePostById($postId);

        return response([], Response::HTTP_NO_CONTENT);
    }
}

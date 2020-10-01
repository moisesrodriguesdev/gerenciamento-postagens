<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\Post as PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $postQuery = Post::query();

        if (!empty($request->get('tag'))) {
            $postQuery = $postQuery->WhereJsonContains('tags', $request->get('tag'));
        }

        return PostResource::collection(
            $postQuery->where(['autor_id' => auth()->user()->id])->get()
        )->collection;
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

        $postCreate = new Post();
        $postCreate->title = $request->title;
        $postCreate->content = $request->content;
        $postCreate->tags = json_encode($request->tags);
        $postCreate->autor_id = auth()->user()->id;
        $postCreate->save();


        return response()->json([$postCreate], Response::HTTP_CREATED);
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

        $post = Post::find($postId);

        $post->update($request->post());

        return $post->getChanges($request->post());
    }

    public function delete(int $postId)
    {
        $post = Post::find($postId);
        $post->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}

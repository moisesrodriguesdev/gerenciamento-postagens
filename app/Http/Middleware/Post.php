<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Post as PostModel;

class Post
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rules = [
            'post_id' => 'required|int|exists:posts,id',
        ];

        $validator = Validator::make(['post_id' => $request->route('postId')], $rules);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!PostModel::where(['autor_id' => auth()->user()->id, 'id' => (int) $request->route('postId')])->exists()) {
            return response()->json([
                'message' => 'This post does not belong to you',
            ], Response::HTTP_UNAUTHORIZED);
        };

        return $next($request);
    }
}

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

    /**
     * Listar todas as postagens cadastradas pelo usuário
     *
     * @OA\Get(
     *      path="/api/post",
     *      operationId="getPosts",
     *      tags={"Postagem"},
     *      summary="Lista Posts",
     *      description="Retorna todas as postagens cadastradas no sistema",
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="tag",
     *          description="Filtrar postagem pela tag",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Executado com sucesso",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          {
     *                              "id": 1,
     *                              "title": "Titulo teste",
     *                              "content": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type",
     *                              "tags": {
     *                                  "C",
     *                                  "PHP",
     *                                  "C#"
     *                              },
     *                              "author": "Moises rodrigues"
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *       ),
     *      @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->postModel->allUserPost($request->query('tag')),
            Response::HTTP_OK
        );
    }


    /**
     * Cadastro de postagens no sistema
     *
     * @OA\Post(
     *      path="/api/post",
     *      operationId="storePost",
     *      tags={"Postagem"},
     *      summary="Cadastro de postagens",
     *      description="Cadastro de postagens",
     *      security={{"apiAuth":{}}},
     *      @OA\RequestBody(
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Titulo do post"),
     *              @OA\Property(property="content", type="string", example="Conteudo do post"),
     *              @OA\Property(
     *                  property="tags",
     *                  type="array",
     *                  example={
     *                      "C",
     *                      "PHP",
     *                      "Java"
     *                  },
     *                  @OA\Items(
     *                      @OA\Property(
     *                         property="tag",
     *                         type="string",
     *                         example="PHP"
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Executado com sucesso",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "title": "Titulo do post",
     *                          "content": "Conteudo do post",
     *                          "tags": {
     *                              "C",
     *                              "PHP",
     *                              "Java"
     *                          },
     *                          "autor_id": 1,
     *                          "id": 1
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "title": {
     *                              "The title has already been taken."
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     * )
     */
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

    /**
     * Atualiza Postagem
     *
     * @OA\Put(
     *      path="/api/post/{postId}",
     *      tags={"Postagem"},
     *      summary="Atualiza Postagem",
     *      description="Atualiza post cadastrado anteriormente no sistema",
     *      operationId="updatePost",
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="postId",
     *          description="ID do post previamente cadastrado",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          description="",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Alterar Titulo do post"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Executado com sucesso",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "title": "Alterar Titulo do post",
     *                          "updated_at": "2020-10-02 00:46:02"
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          {
     *                              "message": "This post does not belong to you"
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          "title": {
     *                              "The title has already been taken."
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     * )
     */
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

    /**
     * Deleta Postagem
     *
     * @OA\Delete(
     *      path="/api/post/{postId}",
     *      operationId="deletePost",
     *      tags={"Postagem"},
     *      summary="Deleta Postagem",
     *      description="Deleta post cadastrado anteriormente no sistema",
     *      security={{"apiAuth":{}}},
     *      @OA\Parameter(
     *          name="postId",
     *          description="ID do post previamente cadastrado",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(response=204,description="Executado com sucesso"),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          content={
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      example={
     *                          {
     *                              "message": "This post does not belong to you"
     *                          }
     *                      }
     *                  )
     *              )
     *          }
     *      ),
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(int $postId)
    {
        $this->postModel->deletePostById($postId);

        return response([], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Gerenciamento de Postagens API",
     *      description="API do projeto Gerenciamento de Postagens",
     *      @OA\Contact(
     *          email="moisesabreurodrigues@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * ),
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Gerenciamento de Postagens API"
     * ),
     *
     * @OA\SecurityScheme(
     *     type="oauth2",
     *     in="header",
     *     scheme="bearer",
     *     securityScheme="apiAuth",
     *     bearerFormat="JWT",
     *     @OA\Flow(
     *         flow="clientCredentials",
     *         tokenUrl="/api/auth/auth",
     *         scopes={}
     *     )
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

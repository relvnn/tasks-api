<?php

namespace App\Http\Controllers\Api\V1\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="API Tasks",
 *      version="1.0.0",
 *      description="Documentação da API de Tasks",
 *      @OA\Contact(
 *          email="contato@exemplo.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api/v1",
 *      description="Servidor local"
 * )
 */
class SwaggerInfo
{
    // Esse arquivo não precisa de código, apenas serve para armazenar as anotações
}

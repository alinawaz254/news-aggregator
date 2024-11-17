<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="This is the API documentation for the News Aggregator project.",
 *      @OA\Contact(
 *          email="alinawaz254@gmail.com"
 *      ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 */
class SwaggerConfig
{
    // This class is just for Swagger configuration and won't contain any methods.
}

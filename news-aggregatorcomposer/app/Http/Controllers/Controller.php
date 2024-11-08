<?php
namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API Documentation",
 *      description="News Aggregator API Documentation",
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://localhost:70/api",
 *      description="API Server"
 * )
 *
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints of Projects"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Authentication using Laravel Sanctum"
 * )
 */

abstract class Controller {}

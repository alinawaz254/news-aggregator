<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\JsonResponse;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function respond($statusCode = 200, $data = [], $message= 'Success', $status = true): JsonResponse
    {
        return response()->json([
            'statusCode' => $statusCode,
            'response' => $data,
            'message' => $message,
            'status' => $status,
        ], $statusCode);
    }

    public function internalError($messaage): JsonResponse
    {
        return response()->json([
            'statusCode' => 500,
            'response' => [],
            'message' => $messaage,
            'status' => false,
        ], 500);
    }
}

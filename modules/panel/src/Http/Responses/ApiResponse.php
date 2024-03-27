<?php

namespace Fpaipl\Panel\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{

    public static function success($data, $message = null){
        $response =[
            'data' => $data,
            'status' => 'ok',
            'message' => $message,
        ];

        return new JsonResponse($response, 200);
    }

    public static function error($message, $statusCode){

        $response = [
            'data' => null,
            'status' => 'error',
            'message' => $message,
        ];

        return new JsonResponse($response, $statusCode);
    }
    
}
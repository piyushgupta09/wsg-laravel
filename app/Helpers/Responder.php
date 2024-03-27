<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Responder
{
    public static function send($status, $message, $data = null, $httpCode = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

    public static function ok($message, $data = null, $httpCode = 200)
    {
        return response()->json([
            'status' => 'ok',
            'message' => $message,
            'data' => $data
        ], $httpCode);
    }

    public static function error($data, $httpCode)
    {
        return response()->json([
            'status' => 'error',
            'errors' => $data
        ], $httpCode);
    }
}

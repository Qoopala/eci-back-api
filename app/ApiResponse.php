<?php

namespace App;

class ApiResponse
{
    static function ok(string $message, $response = null)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $response
        ], 200);
    }

    static function created(string $message, $response = null)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $response
        ], 201);
    }

    static function badRequest($message)
    {
        return response([
            'success' => false,
            'message' => $message
        ], 400);
    }

    static function unauthorized(string $message)
    {
        return response([
            'success' => false,
            'message' => $message
        ], 401);
    }

    static function forbidden(string $message)
    {
        return response([
            'success' => false,
            'message' => $message
        ], 403);
    }

    static function not_found(string $message)
    {
        return response([
            'success' => false,
            'message' => $message
        ], 404);
    }

    static function serverError($message = 'Server error')
    {
        return response([
            'success' => false,
            'message' => $message
        ], 500);
    }
}

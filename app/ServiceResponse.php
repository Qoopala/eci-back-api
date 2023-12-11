<?php

namespace App;

class ServiceResponse
{
    static function ok(string $message, $response = null)
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $response,
            'code' => 200
        ];
    }

    static function created(string $message, $response = null)
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $response,
            'code' => 201
        ];
    }

    static function badRequest($message)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => 400
        ];
    }

    static function unauthorized(string $message)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => 401
        ];
    }

    static function forbidden(string $message)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => 403
        ];
    }

    static function not_found(string $message)
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => 404
        ];
    }

    static function serverError($message = 'Server error')
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => 500
        ];
    }
}

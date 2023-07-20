<?php

namespace App\Traits;
trait ErrorResponseTrait
{

    public function badRequest($message)
    {
        return response()->json([
            "message" => $message
        ],400);
    }
    
    public function authenticationError()
    {
        return response()->json([
            "message" => "AUTHENTICATION_ERROR"
        ],401);
    }
    
    public function authenticationErrorTokenExpired()
    {
        return response()->json([
            "message" => "TOKEN_EXPIRED"
        ],401);
    }
    
    public function forbidden()
    {
        return response()->json([
            "message" => "NOT_AUTHORIZED"
        ],403);
    }
    
    public function serverError($message)
    {
        return response()->json([
            "message" => $message
        ],500);
    }

}

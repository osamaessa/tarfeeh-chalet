<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ErrorResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    use ErrorResponseTrait;
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if($user->type != User::TYPE_ADMIN){
            return $this->forbidden();
        }
        return $next($request);
    }
}

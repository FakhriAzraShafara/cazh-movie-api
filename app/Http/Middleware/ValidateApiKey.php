<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apikey = $request->header('X-API-KEY');

        if (!$apikey || $apikey !== config('app.api_key')) {
            return response()->json([
                'message'=>'Invalid Api Key'
            ], 401);
        }
        return $next($request);
    }
}

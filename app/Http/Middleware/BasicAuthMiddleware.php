<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getUser() !== 'test1test1' || $request->getPassword() !== 'test1test1') {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Invalid credentials.', 401, $headers);
        }

        return $next($request);
    }
}

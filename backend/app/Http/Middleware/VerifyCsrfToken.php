<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        '/register',
        '/login',
    ];

    public function handle($request, \Closure $next)
    {
        if ($request->expectsJson()) {
            return parent::handle($request, $next);
        }

        return response()->json([
            'error' => 'invalid_request',
            'message' => 'Use Accept: application/json in headers'
        ], 400);
    }
}

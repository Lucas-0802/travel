<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        Log::error($exception);

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => 'you_do_not_have_permission_to_perform_this_action'
            ], 403);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => 'record_not_found'], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => 'route_not_found'], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['error' => 'unauthenticated_user'], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'validation_error',
                'messages' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                'error' => 'http_error',
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        return response()->json([
            'error' => 'internal_server_error',
            'message' => config('app.debug') ? $exception->getMessage() : 'internal_server_error'
        ], 500);
    }
}

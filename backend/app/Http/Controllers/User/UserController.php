<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\login\RegisterRequest as LoginRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(LoginRegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $response =$this->userService->register($data);
            return response()->json([ 'status' => $response->status, 'message' => $response->message], $response->status);  
        } catch (\Throwable $th) {
            return response()->json([ 'status' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}

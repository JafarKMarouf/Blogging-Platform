<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->authService->register($validated);
        return ApiResponse::success($data, 'User registered successfully', 201);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->authService->login($validated);
        return ApiResponse::success($data, 'User Logged successfully');
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = request()->user();
        $user->tokens()->delete();
        return ApiResponse::success(null, 'Logged out successfully');
    }


    /**
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        $data = $this->authService->refresh();
        return ApiResponse::success($data, 'Token refreshed successfully');
    }
}

<?php

namespace App\Services;

use App\Enums\TokenAbility;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param $request
     * @return array
     */
    public function register($request): array
    {
        $user = User::query()->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->refresh();
        return $this->generateTokens($user);
    }

    /**
     * @param $request
     * @return array
     * @throws Exception
     */
    public function login($request): array
    {
        if (!Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ])) {
            throw new Exception('Invalid credentials', 401);
        }
        $user = User::query()
            ->where('email', $request['email'])
            ->first();
        return $this->generateTokens($user);
    }

    /**
     * @return array
     */
    public function refresh(): array
    {
        request()->user()->tokens()->delete();
        return $this->generateTokens(request()->user());
    }

    /**
     * @param $user
     * @return array
     */
    protected function generateTokens($user): array
    {
        $atExpireTime = now()->addMinutes(15);
        $rtExpireTime = now()->addWeeks(2);
        $accessToken = $user->createToken('access_token',
            [TokenAbility::ACCESS_API->value], $atExpireTime);
        $refreshToken = $user->createToken('refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value], $rtExpireTime);

        return [
            'accessToken' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Requests\RegisterRequest;

class AuthController extends Controller
{
    use ApiResponse;


public function register(RegisterRequest $request)
{
    $validated = $request->validated();
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['ppaaword']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;
    return $this->apiSuccess([
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
}
public function login(LoginRequest $request)
{
    $validated = $request->valdated();

    if (!Auth::attempt($validated)){
        return $this->apiError('Credentials not match', Response::HTTP_UNAUTHORIZED);
    }

    $user = User::where('email', $validated['email'])->first();
    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->apiSuccess([
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
}
}
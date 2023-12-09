<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        $waiter = $this->userRepository->findByEmail($request->email);

        if (!$waiter || !Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $waiter->createToken('login-token')->plainTextToken;

        return $this->apiResource(
            ['token' => $token],
            message: 'Logged in successfully'
        );
    }
}

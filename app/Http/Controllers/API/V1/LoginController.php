<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private CustomerRepository $customerRepository;
    private UserRepository $userRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        UserRepository $userRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:customers,phone',
            'password' => 'required|string|min:8',
        ]);

        $customer = $this->customerRepository->findByPhone($request->phone);
        $request->merge(['email' => $customer->user->email]);

        if (!$customer || !Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $customer->user->createToken('login-token')->plainTextToken;

        return $this->apiResource(
            ['token' => $token],
            message: 'Logged in successfully'
        );
    }
}

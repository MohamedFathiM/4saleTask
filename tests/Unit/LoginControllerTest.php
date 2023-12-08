<?php

namespace Tests\Unit;

use App\Http\Controllers\API\V1\LoginController;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLoginWithValidCredentials()
    {
        $customer = \App\Models\Customer::factory()->create();
        $user = $customer->user;

        $customerRepositoryMock = Mockery::mock(CustomerRepository::class);
        $customerRepositoryMock->shouldReceive('findByPhone')->andReturn($customer);

        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $userRepositoryMock->shouldReceive('findByEmail')->andReturn($user);
        $loginController = new LoginController($customerRepositoryMock, $userRepositoryMock);

        $request = Request::create('/api/login', 'POST', [
            'phone' => $customer->phone,
            'password' => 'password', // Replace with the correct password
        ]);

        $response = $loginController->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getData(true)['data']);
    }

    public function testLoginWithInvalidCredentials()
    {
        $customerRepositoryMock = Mockery::mock(CustomerRepository::class);
        $customerRepositoryMock->shouldReceive('findByPhone')->andReturnNull();

        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $loginController = new LoginController($customerRepositoryMock, $userRepositoryMock);

        $request = Request::create('/api/login', 'POST', [
            'phone' => '+1234567890', // Replace with a non-existent phone number
            'password' => 'password',
        ]);

        $this->expectException(ValidationException::class);

        $loginController->login($request);
    }
}

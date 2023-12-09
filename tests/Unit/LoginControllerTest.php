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
        $waiter = \App\Models\User::factory()->create();

        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $userRepositoryMock->shouldReceive('findByEmail')->andReturn($waiter);
        $loginController = new LoginController($userRepositoryMock);

        $request = Request::create('/api/login', 'POST', [
            'email' => $waiter->email,
            'password' => 'password', // Replace with the correct password
        ]);

        $response = $loginController->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getData(true)['data']);
    }

    public function testLoginWithInvalidCredentials()
    {
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $loginController = new LoginController($userRepositoryMock);

        $request = Request::create('/api/login', 'POST', [
            'email' => 'mohamed@yahoo.com', // Replace with a non-existent phone number
            'password' => 'password',
        ]);

        $this->expectException(ValidationException::class);

        $loginController->login($request);
    }
}

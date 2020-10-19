<?php

namespace Tests\Unit\Services;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\User;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthUserServiceRegisterTest
 * @package Tests\Unit\Services
 */
class AuthUserServiceRegisterTest extends TestCase
{
    /**
     * @var AuthService
     */
    protected $service;

    /**
     * AuthUserServiceRegisterTest constructor.
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function registerTest()
    {
        $data = [
            'email' => 'test@gmail.com',
            'password' => '112233444',
            'ip_address' => '127.0.0.1'
        ];

        $request = new RegisterRequest($data);

        $this->service->register($request);

        $user = User::create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['password'], bcript($user->password));
        $this->assertEquals($data['ip_address'], $user->ip_address);

        $this->assertTrue(true);
    }
}

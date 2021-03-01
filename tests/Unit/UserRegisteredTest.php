<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\Auth\RegisterFormRequest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserRegisteredTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate(): void
    {
        $request = RegisterFormRequest::create(
            '/api/register',
            'POST',
            [
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => 'test',
            ]
        );

        $controller = new RegisterController();

        $result = $controller->create($request);

        self::assertEquals(Response::HTTP_CREATED, $result['status']);
    }
}

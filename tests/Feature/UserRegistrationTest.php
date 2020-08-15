<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    public function testRegistrationSuccess(): void
    {
        $response = $this->postJson(
            '/register',
            [
                'name' => 'test',
                'email' => 'test@test.test',
                'password' => 'test',
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * @dataProvider registrationFailedDataProvider
     *
     * @param array $data
     * @param array $headers
     */
    public function testRegistrationFail(array $data = [], array $headers = []): void
    {
        $response = $this->postJson(
            '/register',
            $data,
            $headers
        );

        $response->assertStatus(400);
    }

    public function registrationFailedDataProvider(): array
    {
        return [
            'incorrect ip' => [
                [
                    'name' => 'test',
                    'email' => 'test@test.test',
                    'password' => 'test',
                ],
                ['REMOTE_ADDR' => '10.1.0.1']
            ],
            'incorrect domain' => [
                [
                    'name' => 'test',
                    'email' => 'banned@domain.com',
                    'password' => 'test',
                ]
            ],
            'required name' => [
                [
                    'email' => 'test@test.test',
                    'password' => 'test',
                ]
            ],
            'incorrect email' => [
                [
                    'name' => 'test',
                    'email' => 'test',
                    'password' => 'test',
                ]
            ]
        ];
    }
}

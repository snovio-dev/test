<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserRegisteredTest extends TestCase
{
    public function testRequiredFieldsForRegistration(): void
    {
        $this->json('POST', 'api/register', [], ['Accept' => 'application/json'])
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJson([
                 'message' => 'Registration error',
                 'errors' => [                     
                     'email' => ['The email field is required.'],
                     'password' => ['The password field is required.'],
                 ]
             ]);
    }

    public function testRequiredPassword(): void
    {
        $userData = [
            'name' => 'test',
            'email' => 'test@test.com',
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJson([
                 'message' => 'Registration error',
                 'errors' => [
                     'password' => ['The password field is required.']
                 ]
             ]);
    }

    public function testRequiredEmail(): void
    {
        $userData = [
            'name' => 'test',
            'password' => 'test',
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJson([
                 'message' => 'Registration error',
                 'errors' => [
                     'email' => ['The email field is required.']
                 ]
             ]);
    }

    public function testSuccessfulRegistration(): void
    {
        $userData = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'test',
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
             ->assertStatus(Response::HTTP_CREATED)
             ->assertJsonStructure([
                 'success' => true,
                 'status' => Response::HTTP_CREATED,
                 'messages' => 'Registration success'
             ]);
    }
}

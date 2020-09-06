<?php

namespace Tests\Feature;

use App\BannedDomain;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanRegisterUser()
    {
        $response = $this->post('/api/register', [
            'name' => 'user name',
            'email' => 'user@name.com',
            'password' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertSee('ok');

        $this->assertEquals(1, User::whereEmail('user@name.com')->count());
    }

    public function testNotCanRegisterUserWithNotUniqueEmail()
    {
        factory(User::class)->create([
            'email' => 'user@name.com',
        ]);
        $response = $this->post('/api/register', [
            'name' => 'user name unique',
            'email' => 'user@name.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertDontSee('ok');

        $this->assertDatabaseMissing('users', [
            'name' => 'user name unique',
        ]);
    }

    public function testNotCanRegisterUserWithoutName()
    {
        $response = $this->post('/api/register', [
            'email' => 'user@name.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertDontSee('ok');

        $this->assertDatabaseMissing('users', [
            'email' => 'user@name.com',
        ]);
    }

    public function testNotCanRegisterUserWithoutEmail()
    {
        $response = $this->post('/api/register', [
            'name' => 'user name',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertDontSee('ok');

        $this->assertDatabaseMissing('users', [
            'email' => 'user@name.com',
        ]);
    }

    public function testNotCanRegisterUserWithIncorrectEmail()
    {
        $response = $this->post('/api/register', [
            'name' => 'user name',
            'email' => 'incorrect_email',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertDontSee('ok');

        $this->assertDatabaseMissing('users', [
            'email' => 'user@name.com',
        ]);
    }

    public function testNotCanRegisterUserWithDisabledIp()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '123.12.12.342']);

        $response = $this->post('/api/register', [
            'name' => 'user name',
            'email' => 'user@name.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(500)
            ->assertSee('error');

        $this->assertDatabaseMissing('users', [
            'email' => 'user@name.com',
        ]);
    }

    public function testNotCanRegisterUserWithBannedDomain()
    {
        factory(BannedDomain::class)->create([
            'domain' => 'banned.com',
        ]);

        $response = $this->post('/api/register', [
            'name' => 'user name',
            'email' => 'user2@banned.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(500)
            ->assertSee('error');

        $this->assertDatabaseMissing('users', [
            'email' => 'user@name.com',
        ]);
    }
}

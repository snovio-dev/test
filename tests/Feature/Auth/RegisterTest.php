<?php

namespace Tests\Feature\Auth;

use App\Services\DomainService;
use App\Services\IpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Mockery;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $domainServiceMock;
    protected $ipServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ipServiceMock = Mockery::mock(IpService::class);
        $this->instance(IpService::class, $this->ipServiceMock);

        $this->domainServiceMock = Mockery::mock(DomainService::class);
        $this->instance(DomainService::class, $this->domainServiceMock);

        Mail::fake();
    }

    /**
     * @return void
     */
    public function testCreatePositive()
    {
        $this->domainServiceMock
            ->shouldReceive('isBanned')
            ->andReturn(false);

        $this->ipServiceMock
            ->shouldReceive('isEnabled')
            ->andReturn(true);

        $response = $this->post('/auth/register', [
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'name' => $this->faker->name,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCreateNegative()
    {
        $this->domainServiceMock
            ->shouldReceive('isBanned')
            ->andReturn(true);

        $this->ipServiceMock
            ->shouldReceive('isEnabled')
            ->andReturn(false);

        $response = $this->post('/auth/register', [
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'name' => $this->faker->name,
        ]);

        $response->assertStatus(500);
    }
}

<?php

namespace Tests\Unit\Services;

use App\DisabledIp;
use App\Services\IpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IpServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    public function testIsEnabledNegative()
    {
        $ip = factory(DisabledIp::class)->create();
        $service = new IpService();

        $this->assertFalse($service->isEnabled($ip->ip));
    }

    /**
     * @return void
     */
    public function testIsEnabledPositive()
    {
        $ip = $this->faker->ipv4;
        $service = new IpService();

        $this->assertTrue($service->isEnabled($ip));
    }
}

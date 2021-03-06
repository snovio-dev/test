<?php

namespace Tests\Unit\Services;

use App\BannedDomain;
use App\Services\DomainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DomainServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @return void
     */
    public function testIsBannedNegative()
    {
        $domain = $this->faker->domainName;
        $service = new DomainService();

        $this->assertFalse($service->isBanned($domain));
    }

    /**
     * @return void
     */
    public function testIsBannedPositive()
    {
        $domain = factory(BannedDomain::class)->create();
        $service = new DomainService();

        $this->assertTrue($service->isBanned($domain->domain));
    }
}

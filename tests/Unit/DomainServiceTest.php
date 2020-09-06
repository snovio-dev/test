<?php

namespace Tests\Unit;

use App\BannedDomain;
use App\Services\DomainService;
use Mockery;
use Tests\TestCase;

class DomainServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCheckAllowIpAndDomain()
    {
        $domainService = Mockery::mock(DomainService::class, function ($mock) {
            $mock->shouldReceive('isIpAllow')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('isDomainBanned')
                ->once()
                ->andReturn(false);
        })->makePartial();


        $this->assertEquals(true, $domainService->checkAllowIpAndDomain('user@email.com'));
    }

    public function testIsDomainBannedWhenDomainBanned()
    {
        factory(BannedDomain::class)->create([
            'domain' => 'banned.com',
        ]);

        $domainService = new DomainService();
        $isDomainBanned = $domainService->isDomainBanned('user@banned.com');

        $this->assertEquals(true, $isDomainBanned);
    }

    public function testIsDomainBannedWhenDomainNotBanned()
    {
        factory(BannedDomain::class)->create([
            'domain' => 'banned.com',
        ]);

        $domainService = new DomainService();
        $isDomainBanned = $domainService->isDomainBanned('user@notbanned.com');

        $this->assertEquals(false, $isDomainBanned);
    }

    public function testIsIpAllowWhenHaveDisabledIp()
    {
        $domainService = Mockery::mock(DomainService::class, function ($mock) {
            $mock->shouldReceive('getDisabledIps')
                ->once()
                ->andReturn(['1.1.1.1', '2.2.2.2']);
            $mock->shouldReceive('getIpAddress')
                ->once()
                ->andReturn('1.1.1.1');
        })->makePartial();

        $this->assertEquals(false, $domainService->isIpAllow());
    }

    public function testIsIpAllowWhenHaveAllowIp()
    {
        $domainService = Mockery::mock(DomainService::class, function ($mock) {
            $mock->shouldReceive('getDisabledIps')
                ->once()
                ->andReturn(['1.1.1.1', '2.2.2.2']);
            $mock->shouldReceive('getIpAddress')
                ->once()
                ->andReturn('3.3.3.3');
        })->makePartial();

        $this->assertEquals(true, $domainService->isIpAllow());
    }
}

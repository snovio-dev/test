<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

/**
 * Test for middleware CheckIpMiddleware
 */
class CheckIpMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testHandle()
    {
        $middle = new \App\Http\Middleware\CheckIpMiddleware();

        $next = function () {};
        $request = $this->createMock(\Illuminate\Http\Request::class);

        $request->expects($this->once())
            ->method('getClientIp')
            ->willReturn('121.1.5.11');

        $this->expectException(HttpResponseException::class);

        $middle->handle($request, $next);
    }
}

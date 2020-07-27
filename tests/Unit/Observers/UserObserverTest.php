<?php

use App\User;
use App\Observers\UserObserver;
use Tests\TestCase;

/**
 * Test for observer UserObserver
 */
class UserObserverTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreating()
    {
        $user = new User();
        $user->password = 'newpass';

        $observer = new UserObserver();
        $observer->creating($user);

        $this->assertNotEquals('newpass', $user->password);
    }
}

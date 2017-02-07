<?php

/*
 * This file is part of the Capacitor library.
 *
 * (c) Robin van der Vleuten <robin@webstronauts.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Capacitor\Test;

use Capacitor\Capacitor;

class CapacitorTest extends \PHPUnit_Framework_TestCase
{
    public function testCapacitorDispatchesMessagesToSubscribers()
    {
        $capacitor = new Capacitor();

        $capacitor->subscribe(function ($payload) {
            $this->assertSame(1, $payload);
        });

        $capacitor(1);
    }

    public function testCapacitorDispatchesMessagesToSubscribersMultipleTimes()
    {
        $capacitor = new Capacitor();
        $called = 0;

        $capacitor->subscribe(function () use (&$called) {
            $called++;
        });

        $capacitor(1);
        $capacitor(1);

        $this->assertSame(2, $called);
    }

    public function testCapacitorPassesReturnedPayloadToNextSubscriber()
    {
        $capacitor = new Capacitor();

        $capacitor->subscribe(function ($payload) {
            $this->assertSame(1, $payload);
            return $payload + 1;
        });

        $capacitor->subscribe(function ($payload) {
            $this->assertSame(2, $payload);
            return $payload + 1;
        });

        $this->assertSame(3, $capacitor(1));
    }

    public function testSubscribersCanUnsubscribeFromCapacitor()
    {
        $capacitor = new Capacitor();

        $unsubscribe = $capacitor->subscribe(function () {
            $this->fail('The subscribed callable should not be called after unsubscribing');
        });

        $unsubscribe();

        $capacitor('message');
    }

    public function testUnsubscribeCanBeInvokedMultipleTimesWithoutSideEffects()
    {
        $capacitor = new Capacitor();

        $unsubscribe = $capacitor->subscribe(function () {
            $this->fail('The subscribed callable should not be called after unsubscribing');
        });

        $unsubscribe();
        $unsubscribe();

        $capacitor('message');
    }
}

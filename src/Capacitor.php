<?php

/*
 * This file is part of the Capacitor library.
 *
 * (c) Robin van der Vleuten <robin@webstronauts.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Capacitor;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class Capacitor
{
    /**
     * @var callable[]
     */
    private $subscribers = [];

    /**
     * Add any callable as subscriber. It will be called any time a payload is dispatched. The returned callable can be
     * used to unsubscribe again by invoking.
     *
     * @param callable $subscriber
     *
     * @return callable
     */
    public function subscribe(callable $subscriber): callable
    {
        $isSubscribed = true;

        $this->subscribers[] = $subscriber;

        return function () use ($subscriber, &$isSubscribed) {
            if (!$isSubscribed) {
                return;
            }

            $isSubscribed = false;

            $index = array_search($subscriber, $this->subscribers);
            array_splice($this->subscribers, $index, 1);
        };
    }

    /**
     * Dispatches a payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function __invoke($payload)
    {
        if (count($this->subscribers) === 0) {
            return $payload;
        }

        return call_user_func($this->createSubscriberChain(), $payload);
    }

    /**
     * @return callable
     */
    private function createSubscriberChain(): callable
    {
        return array_reduce(array_slice($this->subscribers, 1), function ($a, $b) {
            return function (...$args) use ($a, $b) {
                return $b(call_user_func_array($a, $args));
            };
        }, $this->subscribers[0]);
    }
}

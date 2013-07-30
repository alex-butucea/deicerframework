<?php

namespace Colt\Stdlib\Pubsub;

/**
 * Interface for objects that can publish events to subscribers
 *
 * Api implies topic based filtering upon subscription
 *
 * @category   Colt
 * @package    Stdlib
 * @subpackage Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface PublisherInterface
{
    /**
     * Subscribe an object to recieve events of a given topic
     *
     * @throws NonStringException If $topic is a non string value
     * @param  SubscriberInterface $subscriber The object to subscribe
     * @param  string $topic The topic to subscribe object to
     * @return void
     */
    public function subscribe(SubscriberInterface $subscriber, $topic);

    /**
     * Unsubscribe an object from a given topic
     *
     * @throws NonStringException If $topic is a non string value
     * @param  SubscriberInterface $subscriber The object to unsubscribe
     * @param  string $topic The topic to unsubscribe object from
     * @return void
     */
    public function unsubscribe(SubscriberInterface $subscriber, $topic);

    /**
     * Publish an event to subscribers
     *
     * @param  EventInterface $event The event to publish
     * @return void
     */
    public function publish(EventInterface $event);
}

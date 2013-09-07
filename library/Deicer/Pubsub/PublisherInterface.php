<?php

namespace Deicer\Pubsub;

/**
 * Interface for objects that can publish messages to subscribers
 *
 * Api implies topic based filtering upon subscription
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface PublisherInterface
{
    /**
     * Subscribe an object to recieve messages of a given topic
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
     * Publish an message to subscribers
     *
     * @param  MessageInterface $message The message to publish
     * @return void
     */
    public function publish(MessageInterface $message);
}

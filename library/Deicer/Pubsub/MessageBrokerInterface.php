<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\SubscriberInterface;

/**
 * Message Broker Interface
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface MessageBrokerInterface
{
    /**
     * Add a set of subscribers to the pool
     *
     * @throws LengthException If $subscribers is empty
     * @throws InvalidArgumentException If $subscribers contains non SubscriberInterface
     * @param  array $subscribers List of subscribers to add to pool
     * @return array Subscribers re-indexed by their new pool index
     */
    public function addSubscribers(array $subscribers);

    /**
     * Add a subscriber to the pool
     *
     * @param  SubscriberInterface $subscriber The subscriber to add
     * @return int Index of the subscriber added
     */
    public function addSubscriber(SubscriberInterface $subscriber);

    /**
     * Remove a subscriber from the pool
     *
     * @throws InvalidArgumentException If $index is a non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @param  int $index The index of the subscriber to remove
     * @return MessageBrokerInterface Fluent interface
     */
    public function removeSubscriber($index);

    /**
     * Publish an message to subscribers
     *
     * @param  MessageInterface $message The message to publish
     * @return MessageBrokerInterface Fluent interface
     */
    public function publish(MessageInterface $message);
}

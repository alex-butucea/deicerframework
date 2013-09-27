<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\MessageBrokerInterface;

/**
 * Topic Filtered Message Broker Interface
 *
 * Delivers events to subscribers based on topic filters
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TopicFilteredMessageBrokerInterface extends MessageBrokerInterface
{
    /**
     * Subscribes a registered subscriber to a given topic
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topic is non string
     * 
     * @param  int $subscriberIndex Index of registered subscriber
     * @param  string $topic Topic to subscribe to
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function subscribeToTopic($subscriberIndex, $topic);

    /**
     * Subscribes a registered subscriber to a given list of topics
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topics contains non string
     * 
     * @param  int $subscriberIndex Index of registered subscriber
     * @param  array $topics List of topics to subscribe to
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function subscribeToTopics($subscriberIndex, array $topics);

    /**
     * Unsubscribes a registered subscriber from a given topic
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topic is non string
     * 
     * @param  int $subscriberIndex Index of registered subscriber
     * @param  string $topic Topic to unsubscribe from
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function unsubscribeFromTopic($subscriberIndex, $topic);

    /**
     * Unsubscribes a registered subscriber from a given list of topics
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topics contains non string
     * 
     * @param  int $subscriberIndex Index of pooled subscriber
     * @param  array $topics List of topics to unsubscribe from
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function unsubscribeFromTopics($subscriberIndex, array $topics);
}

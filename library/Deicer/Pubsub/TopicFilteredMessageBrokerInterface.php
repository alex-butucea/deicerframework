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
     * Subscribes a registered subscriber to a given topic(s)
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topic is non string
     * @throws InvalidArgumentException If $topic is non array
     * @throws InvalidArgumentException If $topic is array containing non string
     * 
     * @param  int $subscriberIndex Index of pooled subscriber
     * @param  string|array $topic Topic / list of topics
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function subscribeToTopic($subscriberIndex, $topic);

    /**
     * Unsubscribes a registered subscriber from a given topic(s)
     * 
     * @throws InvalidArgumentException If $subscriberIndex is non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @throws InvalidArgumentException If $topic is non string
     * @throws InvalidArgumentException If $topic is non array
     * @throws InvalidArgumentException If $topic is array containing non string
     * 
     * @param  int $subscriberIndex Index of pooled subscriber
     * @param  string|array $topic Topic / list of topics
     * 
     * @return TopicFilteredMessageBrokerInterface Fluent interface
     */
    public function unsubscribeFromTopic($subscriberIndex, $topic);
}

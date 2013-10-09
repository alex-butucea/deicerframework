<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use InvalidArgumentException;
use Deicer\Pubsub\AbstractMessageBroker;
use Deicer\Pubsub\TopicFilteredMessageBrokerInterface;

/**
 * Message broker that delivers messages based on topic filters
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TopicFilteredMessageBroker extends AbstractMessageBroker implements
    TopicFilteredMessageBrokerInterface
{
    /**
     * Map of (subscriber_index => array (topics))
     * 
     * @var array
     */
    protected $subscriptions = array ();

    /**
     * {@inheritdoc}
     *
     * Also clears topic filters
     */
    public function removeSubscriber($index)
    {
        parent::removeSubscriber($index);
        unset($this->subscriptions[$index]);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Also clears topic filters
     */
    public function removeSubscribers(array $subscriberIndices)
    {
        parent::removeSubscribers($subscriberIndices);

        foreach ($subscriberIndices as $index) {
            unset($this->subscriptions[$index]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message)
    {
        foreach ($this->subscribers as $key => $sub) {
            if (empty($this->subscriptions[$key])) {
                continue;
            } elseif (!in_array($message->getTopic(), $this->subscriptions[$key])) {
                continue;
            } else {
                $sub->update($message);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribeToTopic($subscriberIndex, $topic)
    {
        $this->validateSubscriberIndex($subscriberIndex, __FUNCTION__);
        $this->validateTopic($topic, __FUNCTION__);

        // Normalize subscriptions
        if (empty($this->subscriptions[$subscriberIndex])) {
            $this->subscriptions[$subscriberIndex] = array ();
        }
        
        // Subscribe to topic once only
        if (!in_array($topic, $this->subscriptions[$subscriberIndex])) {
            $this->subscriptions[$subscriberIndex][] = $topic;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribeToTopics($subscriberIndex, array $topics)
    {
        $this->validateSubscriberIndex($subscriberIndex, __FUNCTION__);
        foreach ($topics as $topic) {
            $this->validateTopic($topic, __FUNCTION__);
        }

        // Normalize subscriptions
        if (empty($this->subscriptions[$subscriberIndex])) {
            $this->subscriptions[$subscriberIndex] = array ();
        }

        // Dont over subscribe
        $this->subscriptions[$subscriberIndex] = array_merge(
            $this->subscriptions[$subscriberIndex],
            $topics
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribeFromTopic($subscriberIndex, $topic)
    {
        $this->validateSubscriberIndex($subscriberIndex, __FUNCTION__);
        $this->validateTopic($topic, __FUNCTION__);

        // Not subscribed to any topics - no futher action required
        if (empty($this->subscriptions[$subscriberIndex])) {
            return $this;
        }

        $topics = array_flip($this->subscriptions[$subscriberIndex]);
        if (isset($topics[$topic])) {
            unset($this->subscriptions[$subscriberIndex][$topics[$topic]]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribeFromTopics($subscriberIndex, array $topics)
    {
        $this->validateSubscriberIndex($subscriberIndex, __FUNCTION__);
        foreach ($topics as $topic) {
            $this->validateTopic($topic, __FUNCTION__);
        }

        // Not subscribed to any topics - no futher action required
        if (empty($this->subscriptions[$subscriberIndex])) {
            return $this;
        }

        $this->subscriptions[$subscriberIndex] = array_diff(
            $this->subscriptions[$subscriberIndex],
            $topics
        );

        return $this;
    }

    /**
     * Validates a topic by throwing an exception if invalid
     *
     * @throws InvalidArgumentException If $topic is non string
     * @param  int $invoker Method name that invoked validation
     * @return void
     */
    public function validateTopic($topic, $invoker)
    {
        if (!is_string($topic)) {
            throw new InvalidArgumentException(
                'Non string|array $topic passed in: '
                . __CLASS__ . '::' . $invoker
            );
        } elseif (empty($topic)) {
            throw new InvalidArgumentException(
                'Empty $topic passed in: '
                . __CLASS__ . '::' . $invoker
            );
        }
    }
}

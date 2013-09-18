<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

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

        // Normalize subscriptions and dont over-subscribe
        if (empty($this->subscriptions[$subscriberIndex])) {
            $this->subscriptions[$subscriberIndex] = array ();
        }
        
        // Subscribe to topic once only
        if (is_string($topic)) {
            if (!in_array($topic, $this->subscriptions[$subscriberIndex])) {
                $this->subscriptions[$subscriberIndex][] = $topic;
            }
        } elseif (is_array($topic)) {
            foreach ($topic as $top) {
                if (!in_array($top, $this->subscriptions[$subscriberIndex])) {
                    $this->subscriptions[$subscriberIndex][] = $top;
                }
            }
        }

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
        if (is_string($topic)) {
            if (isset($topics[$topic])) {
                unset($this->subscriptions[$subscriberIndex][$topics[$topic]]);
            }
        } elseif (is_array($topic)) {
            foreach ($topic as $top) {
                if (isset($topics[$top])) {
                    unset($this->subscriptions[$subscriberIndex][$topics[$top]]);
                }
            }
        }

        return $this;
    }

    /**
     * Validates a topic by throwing an exception if invalid
     *
     * @throws InvalidArgumentException If $topic is non string or array
     * @throws InvalidArgumentException If $topic is array containing non string
     *
     * @param  int $invoker Method name that invoked validation
     *
     * @return void
     */
    public function validateTopic($topic, $invoker)
    {
        if (!is_string($topic) && !is_array($topic)) {
            throw new \InvalidArgumentException(
                'Non string|array $topic passed in: '
                . __CLASS__ . '::' . $invoker
            );
        } elseif (empty($topic)) {
            throw new \InvalidArgumentException(
                'Empty $topic passed in: '
                . __CLASS__ . '::' . $invoker
            );
        }

        // Validate array elements if several topics passed
        if (is_array($topic)) {
            foreach ($topic as $top) {
                if (!is_string($top)) {
                    throw new \InvalidArgumentException(
                        'Non string containing array $topic passed in: ' .
                        __CLASS__ . '::' . $invoker
                    );
                }
            }
        }
    }

    protected function innerUnsubscribeFromTopic($subscriberIndex, $topic)
    {

    }
}

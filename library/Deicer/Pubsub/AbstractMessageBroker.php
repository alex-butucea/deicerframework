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
 * Message Broker Base Class
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractMessageBroker
{
    /**
     * Subscriber pool
     *
     * @var array SubscriberInterface
     */
    protected $subscribers = array ();

    /**
     * {@inheritdoc}
     */
    public function addSubscribers(array $subscribers)
    {
        if (empty($subscribers)) {
            throw new \LengthException('Empty $subscribers in: ' . __METHOD__);
        }

        // Enforce subscriber array type strength
        $subs = array ();
        foreach ($subscribers as $sub) {
            if (!$sub instanceof SubscriberInterface) {
                throw new \InvalidArgumentException(
                    'Non SubscriberInterface contained in $subscribers in: ' .
                    __METHOD__
                );
            }

            $subs[] = $sub;
        }

        // Add subscribers to pool and return added subscribers re-indexed
        $ret = array ();
        foreach ($subs as $sub) {
            $ret[$this->addSubscriber($sub)] = $sub;
        }

        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $index = ($this->subscribers) ? max(array_keys($this->subscribers)) + 1 : 0;
        $this->subscribers[$index] = $subscriber;
        return $index;
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber($index)
    {
        $this->validateSubscriberIndex($index, __FUNCTION__);
        unset($this->subscribers[$index]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message)
    {
        foreach ($this->subscribers as $sub) {
            $sub->update($message);
        }

        return $this;
    }

    /**
     * Validates a subscriber index by throwing an exception if invalid
     *
     * @throws InvalidArgumentException If $subscriberIndex is a non int
     * @throws OutOfRangeException If no subscriber exists at $index
     * @param  int $index The subscriber index to validate
     * @param  int $invoker Method name that invoked validation
     *
     * @return void
     */
    protected function validateSubscriberIndex($index, $invoker)
    {
        if (!is_int($index)) {
            throw new \InvalidArgumentException(
                'Non int $index passed in: ' . __CLASS__ . '::' . $invoker
            );
        } elseif (empty($this->subscribers[$index])) {
            throw new \OutOfRangeException(
                'Non-existent subscriber $index in: ' . __CLASS__ . '::' . $invoker
            );
        }
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\QueryInterface;
use Deicer\Query\Message\QueryMessageTopic;
use Deicer\Query\Exception\DataTypeException;
use Deicer\Query\Exception\DataFetchException;
use Deicer\Query\Exception\ModelHydratorException;
use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\SubscriberInterface;
use Deicer\Exception\Type\NonArrayException;
use Deicer\Exception\Type\NonStringException;

/**
 * Abstract Deicer Query
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractQuery
{
    /**
     * Query data provider - DB connection, CURl client, etc.
     * 
     * @var mixed
     */
    protected $dataProvider;

    /**
     * Assembles pubsub messages 
     * 
     * @var InvariableQueryMessageBuilderInterface
     */
    protected $messageBuilder;

    /**
     * Hydrates query responses 
     * 
     * @var RecursiveModelCompositeHydratorInterface
     */
    protected $modelHydrator;

    /**
     * Decorated executable
     *
     * @var QueryInterface
     */
    protected $decorated;

    /**
     * Subscriber instances
     *
     * SubscriberObjectHash => SubscriberObject
     * 
     * @var array
     */
    protected $subscribers = array ();

    /**
     * Associative array of message subscribers to topcis
     *
     * SubscriberObjectHash => array (TopicSubscription, TopicSubscription)
     *
     * @var array
     */
    protected $subscriptions = array ();

    /**
     * Model composite yeilded from last execuction
     * 
     * @var ModelCompositeInterface
     */
    protected $lastResponse;

    /**
     * {@inheritdoc}
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * {@inheritdoc}
     *
     * @throws DataFetchException If unhandled exception is thrown
     * @throws DataTypeException If non array type is returned by implementation
     * @throws ModelHydratorException If data cannot be used to hydrate models
     */
    public function execute()
    {
        // Initialize message and record time in milliseconds
        $this->messageBuilder->withPublisher($this);
        $time = round(microtime() * 1000);

        // Sync selection criteria to reflect instance
        $this->syncMessageBuilder()->syncDecorated();
        // Attempt to fetchData, rethrow exception if no decorated query exists
        try {
            $data = $this->fetchData();
        } catch (\Exception $e) {

            // Build message based on whether execution can fall back to decorated
            $topic = ($this->decorated) ?
                QueryMessageTopic::FALLBACK_DATA_FETCH :
                QueryMessageTopic::FAILURE_DATA_FETCH;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent(array ())
                ->build()
                ->addElapsedTime((int) (round(microtime() * 1000) - $time));
            $this->publish($message);

            // Update last response with decorated result and terminate
            if ($this->decorated) {
                $this->lastResponse = $this->decorated->execute();
                return $this->lastResponse;
            } else {

                // Leave last response intact and terminate execution
                throw new DataFetchException(
                    'Unhandled data provider exception in: ' .
                    get_called_class() . '::' . __FUNCTION__,
                    0,
                    $e
                );
            }
        }

        // Enforce returned data type strength if no decorated query exists
        if (! is_array($data)) {
            $topic = ($this->decorated) ?
                QueryMessageTopic::FALLBACK_DATA_TYPE :
                QueryMessageTopic::FAILURE_DATA_TYPE;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent(array ())
                ->build()
                ->addElapsedTime((int) (round(microtime() * 1000) - $time));
            $this->publish($message);

            // Update last response with decorated result and terminate
            if ($this->decorated) {
                $this->lastResponse = $this->decorated->execute();
                return $this->lastResponse;
            } else {

                // Leave last response intact and terminate execution
                throw new DataTypeException(
                    'Non array data provider response returned in: ' .
                    get_called_class() . '::' . __FUNCTION__
                );
            }
        }

        // Attempt to hydrate model composite and fall back to decorated on fail
        try {
            $hydrated = clone $this->modelHydrator->exchangeArray($data);
        } catch (\Exception $e) {

            // Build message based on whether execution can fall back to decorated
            $topic = ($this->decorated) ?
                QueryMessageTopic::FALLBACK_MODEL_HYDRATOR :
                QueryMessageTopic::FAILURE_MODEL_HYDRATOR;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent($data)
                ->build()
                ->addElapsedTime((int) (round(microtime() * 1000) - $time));
            $this->publish($message);

            // Update last response with decorated result and terminate
            if ($this->decorated) {
                $this->lastResponse = $this->decorated->execute();
                return $this->lastResponse;
            } else {

                // Leave last response intact and terminate execution
                throw new ModelHydratorException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e
                );
            }
        }

        $this->lastResponse = $hydrated;

        // Notify subscribers of successful query execution
        $message = $this->messageBuilder
            ->withTopic(QueryMessageTopic::SUCCESS)
            ->withContent($data)
            ->build()
            ->addElapsedTime((int) (round(microtime() * 1000) - $time));
        $this->publish($message);

        return $hydrated;
    }

    /**
     * {@inheritdoc}
     *
     * Only permits object instances to subscribe to a given topic once
     *
     * @throws InvalidArgumentException If $topic is empty
     */
    public function subscribe(SubscriberInterface $subscriber, $topic)
    {
        $this->validateTopic($topic);

        // Generate subscriber hash and store instance
        $hash = spl_object_hash($subscriber);
        $this->subscribers[$hash] = $subscriber;

        // Normalise subscriber topic subscriptions
        if (! isset($this->subscriptions[$hash])) {
            $this->subscriptions[$hash] = array ();
        }
        
        // Deny subscribers from subscribing to the same topic more than once
        if (! in_array($topic, $this->subscriptions[$hash])) {
            $this->subscriptions[$hash][] = $topic;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws OutOfBoundsException If $subscriber is not registered
     * @throws OutOfBoundsException If $subscriber is not subscribed to $topic
     */
    public function unsubscribe(SubscriberInterface $subscriber, $topic)
    {
        $this->validateTopic($topic);
        
        // Generate subscriber hash and terminate execution if not subscribed
        $hash = spl_object_hash($subscriber);
        if (empty($this->subscribers[$hash]) ||
            empty($this->subscriptions[$hash])
        ) {
            throw new \OutOfBoundsException(
                'Unsubscribed $subscriber passed for unsubscription in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
        }

        // Locate topic subscription and terminate if not subscribed
        $index = array_search($topic, $this->subscriptions[$hash]);
        if ($index === false) {
            throw new \OutOfBoundsException(
                'Unsubscribed $topic passed for unsubscription in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
        }

        // Unsubscribe from topic and return fluent interface
        unset($this->subscriptions[$hash][$index]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message)
    {
        // Walk topic subscriptions only notifying if message topic matches
        foreach ($this->subscriptions as $subHash => $topics) {
            if (in_array($message->getTopic(), $topics)) {
                $this->subscribers[$subHash]->update($message);
            }
        }

        return $this;
    }

    /**
     * Throws exception if given topic is invalid
     *
     * @throws NonStringException If $topic is a non string value
     * @throws InvalidArgumentException If $topic is empty
     * @param  string $topic PubSub topic to validate
     * @return void
     */
    protected function validateTopic($topic)
    {
        if (! is_string($topic)) {
            throw new NonStringException();
        } elseif (empty($topic)) {
            throw new \InvalidArgumentException(
                '$topic must not be empty string in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
        }
    }

    /**
     * Sync message builder selection criteria with instance
     * 
     * @return AbstractQuery Fluent interface
     */
    abstract protected function syncMessageBuilder();

    /**
     * Sync decorated query selection criteria with instance
     * 
     * @return AbstractQuery Fluent interface
     */
    abstract protected function syncDecorated();

    /**
     * Concrete execution logic
     * 
     * Called by execute() - expects indexed array of associative array(s)
     * Returned array used to hydrate models - must match model properties
     * 
     * @return array
     */
    abstract protected function fetchData();
}

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
use Deicer\Query\MessageTopic;
use Deicer\Query\Exception\DataTypeException;
use Deicer\Query\Exception\DataFetchException;
use Deicer\Query\Exception\ModelHydratorException;
use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\MessageBuilderInterface;
use Deicer\Pubsub\UnfilteredMessageBrokerInterface;
use Deicer\Pubsub\TopicFilteredMessageBrokerInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;

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
     * @var MessageBuilderInterface
     */
    protected $messageBuilder;

    /**
     * Pubsub message broker with no filtering
     * 
     * @var UnfilteredMessageBrokerInterface
     */
    protected $unfilteredBroker;

    /**
     * Pubsub message broker with topic filtering
     * 
     * @var TopicFilteredMessageBrokerInterface
     */
    protected $topicFilteredBroker;

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
     * Model composite yeilded from last execuction
     * 
     * @var ModelCompositeInterface
     */
    protected $lastResponse;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $dataProvider,
        MessageBuilderInterface $messageBuilder,
        UnfilteredMessageBrokerInterface $unfilteredBroker,
        TopicFilteredMessageBrokerInterface $topicFilteredBroker,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    ) {
        $this->dataProvider        = $dataProvider;
        $this->messageBuilder      = $messageBuilder;
        $this->unfilteredBroker    = $unfilteredBroker;
        $this->topicFilteredBroker = $topicFilteredBroker;
        $this->modelHydrator       = $modelHydrator;
        $this->lastResponse        = $modelHydrator->exchangeArray(array ());
    }

    /**
     * {@inheritdoc}
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnfilteredMessageBroker()
    {
        return $this->unfilteredBroker;
    }

    /**
     * {@inheritdoc}
     */
    public function getTopicFilteredMessageBroker()
    {
        return $this->topicFilteredBroker;
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
        $time = round(microtime(true) * 1000);

        // Sync selection criteria to reflect instance
        $this->syncDecorated();

        // Attempt to fetchData, rethrow exception if no decorated query exists
        try {
            $data = $this->fetchData();
        } catch (\Exception $e) {

            // Build message based on whether execution can fall back to decorated
            $topic = ($this->decorated) ?
                MessageTopic::FALLBACK_DATA_FETCH :
                MessageTopic::FAILURE_DATA_FETCH;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent(null)
                ->withAttributes(
                    $this->getSupplementaryMessageAttributes() +
                    array ('elapsed_time' => $this->calculateElapsedTime($time))
                )
                ->build();
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
                MessageTopic::FALLBACK_DATA_TYPE :
                MessageTopic::FAILURE_DATA_TYPE;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent(null)
                ->withAttributes(
                    $this->getSupplementaryMessageAttributes() +
                    array ('elapsed_time' => $this->calculateElapsedTime($time))
                )
                ->build();
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
                MessageTopic::FALLBACK_MODEL_HYDRATOR :
                MessageTopic::FAILURE_MODEL_HYDRATOR;
            $message = $this->messageBuilder
                ->withTopic($topic)
                ->withContent($data)
                ->withAttributes(
                    $this->getSupplementaryMessageAttributes() +
                    array ('elapsed_time' => $this->calculateElapsedTime($time))
                )
                ->build();
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
            ->withTopic(MessageTopic::SUCCESS)
            ->withContent($data)
            ->withAttributes(
                $this->getSupplementaryMessageAttributes() +
                array ('elapsed_time' => $this->calculateElapsedTime($time))
            )
            ->build();
        $this->publish($message);

        return $hydrated;
    }

    /**
     * Calcualtes the elapsed time (ms) since a given start point
     * 
     * @param  int $start Start to calcualte elapsed time from
     * @return int
     */
    protected function calculateElapsedTime($start)
    {
        return (int) (round(microtime(true) * 1000) - $start);
    }

    /**
     * Publishes message using registered message brokers
     * 
     * @param  MessageInterface $message Message to publish
     * @return void
     */
    protected function publish(MessageInterface $message)
    {
        $this->unfilteredBroker->publish($message);
        $this->topicFilteredBroker->publish($message);
    }

    /**
     * Get supplementary attributes to inject into published messages
     *
     * @return array
     */
    abstract protected function getSupplementaryMessageAttributes();

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

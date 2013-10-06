<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Stdlib\ExecutableInterface;
use Deicer\Pubsub\MessageBuilderInterface;
use Deicer\Pubsub\UnfilteredPublisherInterface;
use Deicer\Pubsub\TopicFilteredPublisherInterface;
use Deicer\Pubsub\UnfilteredMessageBrokerInterface;
use Deicer\Pubsub\TopicFilteredMessageBrokerInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;

/**
 * Deicer Query Interface
 *
 * Read-only DAO for fetching Models from API, DB, cache or other storage.
 * Implements a decorator pattern for auto-failover functionality.
 * Publishes topic-filtered messages to subcribers on execution.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface QueryInterface extends
 ExecutableInterface,
 UnfilteredPublisherInterface,
 TopicFilteredPublisherInterface
{
    /**
     * Query Constructor
     *
     * @param  mixed $dataProvider Query data provider - DB connection, CURl client, etc.
     * @param  MessageBuilderInterface $messageBuilder Assembles pubsub messages
     * @param  UnfilteredPublisherInterface $unfilteredBroker Unfiltered message broker
     * @param  TopicFilteredMessageBrokerInterface $topicFilteredBroker Topic filtered broker
     * @param  RecursiveModelCompositeHydratorInterface $modelHydrator Hydrates query responses
     *
     * @return QueryInterface
     */
    public function __construct(
        $dataProvider,
        MessageBuilderInterface $messageBuilder,
        UnfilteredMessageBrokerInterface $unfilteredBroker,
        TopicFilteredMessageBrokerInterface $topicFilteredBroker,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    );

    /**
     * Returns the last model composite yeilded from query execution
     * 
     * @return ModelCompositeInterface
     */
    public function getLastResponse();
}

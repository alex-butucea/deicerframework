<?php

namespace Deicer\Query;

use Deicer\Query\QueryInterface;
use Deicer\Query\Event\TokenizedQueryEventBuilderInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;
use Deicer\Stdlib\TokenConsumerInterface;
use Deicer\Stdlib\TokenProviderInterface;
use Deicer\Stdlib\Exe\ExecutableInterface;
use Deicer\Stdlib\Pubsub\PublisherInterface;

/**
 * Marker interface for tokenized queries
 *
 * Tokenized query used for fetching models using an id, slug or other token.
 * Use cases would be retrieving a unique / sigular model from a data store or
 * all models matching a given tag / search term.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenizedQueryInterface extends
 QueryInterface,
 ExecutableInterface,
 PublisherInterface,
 TokenConsumerInterface,
 TokenProviderInterface
{
    /**
     * Tokenized Query Constructor
     *
     * @param  mixed $dataProvider Query data provider - DB connection, CURl client, etc.
     * @param  TokenizedQueryEventBuilderInterface $eventBuilder Assembles pubsub events
     * @param  RecursiveModelCompositeHydratorInterface $modelHydrator Hydrates query responses
     * @return TokenizedQueryInterface
     */
    public function __construct(
        $dataProvider,
        TokenizedQueryEventBuilderInterface $eventBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    );

    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  TokenizedQueryInterface $decoratable The query to decorate
     * @retrun TokenizedQueryInterface Fluent interface
     */
    public function decorate(TokenizedQueryInterface $decoratable);
}

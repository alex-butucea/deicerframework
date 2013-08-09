<?php

namespace Deicer\Query;

use Deicer\Query\QueryInterface;
use Deicer\Query\Event\ParametizedQueryEventBuilderInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;
use Deicer\Stdlib\Exe\ExecutableInterface;
use Deicer\Stdlib\Pubsub\PublisherInterface;
use Deicer\Stdlib\ParameterConsumerInterface;

/**
 * {@inheritdoc}
 *
 * Parametized query used for fetching models using a set of key-value pairs.
 * Use cases would be retrieving several models from a collection using a
 * search algorithm with multiple filters.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParametizedQueryInterface extends
 QueryInterface,
 ExecutableInterface,
 PublisherInterface,
 ParameterConsumerInterface
{
    /**
     * Parametized Query Constructor
     *
     * @param  mixed $dataProvider Query data provider - DB connection, CURl client, etc.
     * @param  ParametizedQueryEventBuilderInterface $eventBuilder Assembles pubsub events
     * @param  RecursiveModelCompositeHydratorInterface $modelHydrator Hydrates query responses
     * @return ParametizedQueryInterface
     */
    public function __construct(
        $dataProvider,
        ParametizedQueryEventBuilderInterface $eventBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    );

    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  ParametizedQueryInterface $decoratable The query to decorate
     * @retrun ParametizedQueryInterface Fluent interface
     */
    public function decorate(ParametizedQueryInterface $decoratable);

    /**
     * Attempt to set multiple query parameters, skipping invalid params
     * 
     * @param  array $params Parameters to attempt to set
     * @retrun ParametizedQueryInterface Fluent interface
     */
    public function trySetParams(array $params);
}

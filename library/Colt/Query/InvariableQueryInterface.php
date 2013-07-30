<?php

namespace Colt\Query;

use Colt\Query\QueryInterface;
use Colt\Query\Event\InvariableExecutionEventBuilderInterface;
use Colt\Model\RecursiveModelCompositeHydratorInterface;
use Colt\Stdlib\Exe\ExecutableInterface;
use Colt\Stdlib\Pubsub\PublisherInterface;

/**
 * {@inheritdoc}
 *
 * Invariable query used for fetching models using a fixed algorithm.
 * Use cases would be retrieving all models from a given collection or fetching
 * a unique / sigular model from a data store.
 *
 * @category   Colt
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface InvariableQueryInterface extends
 QueryInterface,
 ExecutableInterface,
 PublisherInterface
{
    /**
     * Invariable Query Constructor
     *
     * @param  mixed $dataProvider Query data provider - DB connection, CURl client, etc.
     * @param  InvariableExecutionEventBuilderInterface $eventBuilder Assembles pubsub events
     * @param  RecursiveModelCompositeHydratorInterface $modelHydrator Hydrates query responses
     * @return InvariableQueryInterface
     */
    public function __construct(
        $dataProvider,
        InvariableExecutionEventBuilderInterface $eventBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    );

    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  InvariableQueryInterface $decoratable The query to decorate
     * @retrun InvariableQueryInterface Fluent interface
     */
    public function decorate(InvariableQueryInterface $decoratable);
}

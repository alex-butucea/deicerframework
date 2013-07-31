<?php

namespace Deicer\Query;

use Deicer\Query\QueryInterface;
use Deicer\Query\Event\InvariableQueryEventBuilderInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;
use Deicer\Stdlib\Exe\ExecutableInterface;
use Deicer\Stdlib\Pubsub\PublisherInterface;

/**
 * {@inheritdoc}
 *
 * Invariable query used for fetching models using a fixed algorithm.
 * Use cases would be retrieving all models from a given collection or fetching
 * a unique / sigular model from a data store.
 *
 * @category   Deicer
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
     * @param  InvariableQueryEventBuilderInterface $eventBuilder Assembles pubsub events
     * @param  RecursiveModelCompositeHydratorInterface $modelHydrator Hydrates query responses
     * @return InvariableQueryInterface
     */
    public function __construct(
        $dataProvider,
        InvariableQueryEventBuilderInterface $eventBuilder,
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

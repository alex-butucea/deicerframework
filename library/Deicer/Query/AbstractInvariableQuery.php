<?php

namespace Deicer\Query;

use Deicer\Query\AbstractQuery;
use Deicer\Query\InvariableQueryInterface;
use Deicer\Query\Event\InvariableQueryEventBuilderInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;

/**
 * {@inheritdoc}
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractInvariableQuery extends AbstractQuery implements
     InvariableQueryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        $dataProvider,
        InvariableQueryEventBuilderInterface $eventBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    ) {
        $this->dataProvider  = $dataProvider;
        $this->eventBuilder  = $eventBuilder;
        $this->modelHydrator = $modelHydrator;
        $this->lastResponse  = $modelHydrator->exchangeArray(array ());
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(InvariableQueryInterface $decoratable)
    {
        $this->decorated = $decoratable;
        $this->syncEventBuilder();
        $this->syncDecorated();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Invariable queries have no selection citeria
     */
    protected function syncEventBuilder()
    {
        return;
    }

    /**
     * {@inheritdoc}
     *
     * Invariable queries have no selection citeria
     */
    protected function syncDecorated()
    {
        return;
    }
}

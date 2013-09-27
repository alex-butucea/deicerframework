<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\AbstractQuery;
use Deicer\Query\InvariableQueryInterface;
use Deicer\Query\Message\InvariableQueryMessageBuilderInterface;
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
        InvariableQueryMessageBuilderInterface $messageBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    ) {
        $this->dataProvider  = $dataProvider;
        $this->messageBuilder  = $messageBuilder;
        $this->modelHydrator = $modelHydrator;
        $this->lastResponse  = $modelHydrator->exchangeArray(array ());

        $this->syncMessageBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(InvariableQueryInterface $decoratable)
    {
        $this->decorated = $decoratable;
        $this->syncDecorated();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Invariable queries have no selection citeria
     */
    protected function syncMessageBuilder()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Invariable queries have no selection citeria
     */
    protected function syncDecorated()
    {
        return $this;
    }
}

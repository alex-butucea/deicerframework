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
    public function decorate(InvariableQueryInterface $decorable)
    {
        $this->decorated = $decorable;
        $this->syncDecorated();

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

    /**
     * {@inheritdoc}
     *
     * Invariable queries have no selection citeria
     */
    protected function getSupplementaryMessageAttributes()
    {
        return array ();
    }
}

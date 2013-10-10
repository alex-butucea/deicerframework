<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\Exception\InvalidArgumentException;
use Deicer\Query\AbstractQuery;
use Deicer\Query\IdentifiedQueryInterface;

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
abstract class AbstractIdentifiedQuery extends AbstractQuery implements
     IdentifiedQueryInterface
{
    /**
     * Selection id
     * 
     * @var int
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function decorate(IdentifiedQueryInterface $decorable)
    {
        $this->decorated = $decorable;
        $this->syncDecorated();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        if (!is_null($id) && !is_int($id)) {
            throw new InvalidArgumentException(
                'Non-int $id passed in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
        }

        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    protected function syncDecorated()
    {
        if ($this->decorated) {
            $this->decorated->setId($this->id);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupplementaryMessageAttributes()
    {
        return array ('id' => $this->id);
    }
}

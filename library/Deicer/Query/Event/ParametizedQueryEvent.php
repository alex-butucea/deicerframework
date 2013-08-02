<?php

namespace Deicer\Query\Event;

use Deicer\Query\ParametizedQueryInterface;
use Deicer\Query\Event\ParametizedQueryEventInterface;
use Deicer\Exception\Type\NonStringException;

/**
 * A representation of an event generated by an parametized query
 *
 * Implements an Immutable Object pattern
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedQueryEvent extends AbstractQueryEvent implements
    ParametizedQueryEventInterface
{
    /**
     * Parameters used in query
     * 
     * @var array
     */
    protected $params = array ();

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $topic,
        $content,
        ParametizedQueryInterface $publisher,
        array $params
    ) {
        if (! is_string($topic)) {
            throw new NonStringException();
        }

        $this->topic = $topic;
        $this->content = $content;
        $this->publisher = $publisher;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     *
     * @throws OutOfBoundsException If $name doesnt exist in param set
     */
    public function getParam($name)
    {
        if (! array_key_exists($name, $this->params)) {
            throw new \OutOfBoundsException('Invalid param requested in: ' . __METHOD__);
        }

        return $this->params[$name];
    }
}
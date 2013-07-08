<?php

namespace Colt\Query\Event;

use Colt\Query\Event\ExecutionEventInterface;
use Colt\Stdlib\Pubsub\EventInterface;
use Colt\Stdlib\Exe\ExecutionInterface;
use Colt\Exception\Type\NonIntException;

/**
 * An abstract representation of a query exection event
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractExecutionEvent implements
     ExecutionEventInterface,
     EventInterface,
     ExecutionInterface
{
    /**
     * {@inheritdoc}
     */
    protected $topic = '';

    /**
     * {@inheritdoc}
     *
     * @var mixed
     */
    protected $content;

    /**
     * {@inheritdoc}
     *
     * @var QueryInterface
     */
    protected $publisher;

    /**
     * {@inheritdoc}
     *
     * @var int
     */
    protected $elapsedTime = 0;

    /**
     * {@inheritdoc}
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     *
     * @return QueryInterface
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * Add to the total time taken to execute
     *
     * @throws NonIntException If $interval is not an integer
     * @throws RangeException If $interval is negative
     * @param  int $interval The time to add to the total elapsed time
     * @return AbstractExecutionEvent Fluent inteface
     */
    public function addElapsedTime($interval)
    {
        if (! is_int($interval)) {
            throw new NonIntException();
        } elseif ($interval < 0) {
            throw new \RangeException('Negative value given in: ' . __METHOD__);
        }

        $this->elapsedTime += $interval;
        return $this;
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Message;

use Deicer\Exception\Type\NonIntException;

/**
 * An abstract representation of a query message
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractQueryMessage
{
    /**
     * The message topic
     *
     * @param string
     */
    protected $topic = '';

    /**
     * The content payload
     *
     * @var mixed
     */
    protected $content;

    /**
     * Message publisher
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
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonIntException If $interval is not an integer
     * @throws RangeException If $interval is negative
     * @return AbstractQueryMessage Fluent inteface
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

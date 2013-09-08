<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Message;

use Deicer\Query\ParameterizedQueryInterface;
use Deicer\Query\Message\ParameterizedQueryMessageInterface;
use Deicer\Exception\Type\NonStringException;

/**
 * A representation of an message generated by an parameterized query
 *
 * Implements an Immutable Object pattern
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryMessage extends AbstractQueryMessage implements
    ParameterizedQueryMessageInterface
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
        ParameterizedQueryInterface $publisher,
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
     * Retruns topic, execution time, json encoded params and content
     *
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            self::SERIALIZED_FORMAT,
            get_class($this->publisher),
            $this->topic,
            $this->elapsedTime,
            json_encode($this->params),
            json_encode($this->content)
        );
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

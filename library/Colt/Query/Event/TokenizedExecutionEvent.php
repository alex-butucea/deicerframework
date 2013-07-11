<?php

namespace Colt\Query\Event;

use Colt\Query\TokenizedQueryInterface;
use Colt\Stdlib\TokenProviderInterface;
use Colt\Exception\Type\NonStringException;

/**
 * A representation of a query execution event generated by an tokenized query
 *
 * Implements an Immutable Object pattern
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedExecutionEvent extends AbstractExecutionEvent implements
    TokenizedExecutionEventInterface,
    TokenProviderInterface
{
    /**
     * Unique token
     * 
     * @var mixed
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $topic,
        $content,
        TokenizedQueryInterface $publisher,
        $token
    ) {
        if (! is_string($topic)) {
            throw new NonStringException();
        } if (! is_string($token)) {
            throw new NonStringException();
        }

        $this->topic = $topic;
        $this->content = $content;
        $this->publisher = $publisher;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }
}

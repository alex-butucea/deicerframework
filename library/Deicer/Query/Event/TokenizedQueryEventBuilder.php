<?php

namespace Deicer\Query\Event;

use Deicer\Query\Event\TokenizedQueryEvent;
use Deicer\Query\Event\TokenizedQueryEventBuilderInterface;
use Deicer\Pubsub\AbstractEventBuilder;
use Deicer\Exception\Type\NonStringException;

/**
 * Assembles instances of Tokenized Query Events
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedQueryEventBuilder extends AbstractEventBuilder implements
    TokenizedQueryEventBuilderInterface
{
    /**
     * Unique token to build with
     * 
     * @var string
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    public function withToken($token)
    {
        if (! is_string($token)) {
            throw new NonStringException();
        }

        $this->token = $token;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws LogicException If topic is empty
     * @throws LogicException If content has not been set
     * @throws LogicException If publisher has not been set
     * @throws LogicException If token has not been set
     */
    public function build()
    {
        if (empty($this->topic)) {
            throw new \LogicException(
                'Topic required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->content)) {
            throw new \LogicException(
                'Content required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->publisher)) {
            throw new \LogicException(
                'Publisher required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->token)) {
            throw new \LogicException(
                'Token required for build in: ' . __METHOD__
            );
        }

        return new TokenizedQueryEvent(
            $this->topic,
            $this->content,
            $this->publisher,
            $this->token
        );
    }
}

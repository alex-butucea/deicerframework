<?php

namespace Deicer\Query\Message;

use Deicer\Query\Message\InvariableQueryMessage;
use Deicer\Query\Message\InvariableQueryMessageBuilderInterface;
use Deicer\Pubsub\AbstractMessageBuilder;

/**
 * Assembles instances of Invariable Query Messages
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryMessageBuilder extends AbstractMessageBuilder implements
    InvariableQueryMessageBuilderInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LogicException If topic is empty
     * @throws LogicException If content has not been set
     * @throws LogicException If publisher has not been set
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
        }

        return new InvariableQueryMessage(
            $this->topic,
            $this->content,
            $this->publisher
        );
    }
}

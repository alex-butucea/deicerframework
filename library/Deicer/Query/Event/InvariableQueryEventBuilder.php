<?php

namespace Deicer\Query\Event;

use Deicer\Query\Event\InvariableQueryEvent;
use Deicer\Query\Event\InvariableQueryEventBuilderInterface;
use Deicer\Pubsub\AbstractEventBuilder;

/**
 * Assembles instances of Invariable Query Events
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryEventBuilder extends AbstractEventBuilder implements
    InvariableQueryEventBuilderInterface
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

        return new InvariableQueryEvent(
            $this->topic,
            $this->content,
            $this->publisher
        );
    }
}

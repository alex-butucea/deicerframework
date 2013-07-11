<?php

namespace Colt\Query\Event;

use Colt\Query\Event\InvariableExecutionEvent;
use Colt\Query\Event\InvariableExecutionEventBuilderInterface;
use Colt\Stdlib\Pubsub\AbstractEventBuilder;
use Colt\Stdlib\Pubsub\EventBuilderInterface;
use Colt\Exception\Type\NonStringException;

/**
 * Assembles instances of Invariable Query Execution Events
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableExecutionEventBuilder extends AbstractEventBuilder implements
    InvariableExecutionEventBuilderInterface,
    EventBuilderInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LogicException If topic has not been set
     * @throws LogicException If content has not been set
     * @throws LogicException If publisher has not been set
     */
    public function build()
    {
        if (empty($this->topic)) {
            throw new \LogicException('Topic required for build in: ' . __METHOD__);
        } if (! isset($this->content)) {
            throw new \LogicException('Content required for build in: ' . __METHOD__);
        } if (empty($this->publisher)) {
            throw new \LogicException('Publisher required for build in: ' . __METHOD__);
        }

        return new InvariableExecutionEvent(
            $this->topic,
            $this->content,
            $this->publisher
        );
    }
}

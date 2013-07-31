<?php

namespace Deicer\Stdlib\Pubsub;

/**
 * Interface for objects that can subscribe to published events
 *
 * @category   Deicer
 * @package    Stdlib
 * @subpackage Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface SubscriberInterface
{
    /**
     * Update instance with a published event
     *
     * @param  EventInterface $event The event published
     * @return void
     */
    public function update(EventInterface $event);
}

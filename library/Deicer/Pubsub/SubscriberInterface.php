<?php

namespace Deicer\Pubsub;

/**
 * Interface for objects that can subscribe to published messages
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface SubscriberInterface
{
    /**
     * Update instance with a published message
     *
     * @param  MessageInterface $message The message published
     * @return void
     */
    public function update(MessageInterface $message);
}

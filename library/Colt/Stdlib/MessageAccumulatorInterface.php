<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that can accumulate string messages for later retrieval
 *
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface MessageAccumulatorInterface
{
    /**
     * Retrieve accumulated messages
     *
     * @return array
     */
    public function getMessages();

    /**
     * Accumulate a message
     *
     * @param  string $message The message to add
     * @retrun void
     */
    public function addMessage($message);
}

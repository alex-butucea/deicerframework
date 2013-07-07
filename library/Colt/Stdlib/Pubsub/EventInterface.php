<?php

namespace Colt\Stdlib\Pubsub;

/**
 * Representation of an event raised by a publisher object
 *
 * @category   Colt
 * @package    Stdlib
 * @subpackage Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface EventInterface
{
    /**
     * Get the event topic
     *
     * @return string
     */
    public function getTopic();

    /**
     * Get the content payload
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Get the event publisher
     *
     * @return PublisherInterface
     */
    public function getPublisher();
}

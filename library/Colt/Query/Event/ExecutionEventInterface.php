<?php

namespace Colt\Query\Event;

/**
 * Marker interface for query exection events
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExecutionEventInterface
{
    /**
     * Pubsub topic implying execution was a success
     * 
     * @const string
     */
    const EVENT_SUCCESS = 'success';

    /**
     * Pubsub topic implying execution was a failure
     * 
     * @const string
     */
    const EVENT_FAILURE = 'failure';
}

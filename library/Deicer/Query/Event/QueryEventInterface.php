<?php

namespace Deicer\Query\Event;

use Deicer\Stdlib\StringSerializableInterface;
use Deicer\Pubsub\EventInterface;
use Deicer\Stdlib\Exe\ExecutionInterface;

/**
 * Marker interface for query events
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface QueryEventInterface extends
 EventInterface,
 ExecutionInterface,
 StringSerializableInterface
{
    /**
     * Pubsub topic implying execution was a success
     *
     * @const string
     */
    const TOPIC_SUCCESS = 'success';

    // Query Failure Topics

    /**
     * Pubsub topic implying query was a failure and caused by
     * implementation of fetchData() returning array incompatible with models
     *
     * @const string
     */
    const TOPIC_FAILURE_MODEL_HYDRATOR = 'failure_model_hydrator';

    /**
     * Pubsub topic implying query was a failure and caused by
     * implementation of fetchData() throwing an exception
     *
     * @const string
     */
    const TOPIC_FAILURE_DATA_FETCH = 'failure_data_fetch';

    /**
     * Pubsub topic implying execution was a failure and caused by
     * implementation of fetchData() returning non-array
     *
     * @const string
     */
    const TOPIC_FAILURE_DATA_TYPE = 'failure_data_type';

    // Query Fallback Topics

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() returning array incompatible with models
     *
     * @const string
     */
    const TOPIC_FALLBACK_MODEL_HYDRATOR = 'fallback_model_hydrator';

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() throwing an exception
     *
     * @const string
     */
    const TOPIC_FALLBACK_DATA_FETCH = 'fallback_data_fetch';

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() returning non-array
     *
     * @const string
     */
    const TOPIC_FALLBACK_DATA_TYPE = 'fallback_data_type';
}

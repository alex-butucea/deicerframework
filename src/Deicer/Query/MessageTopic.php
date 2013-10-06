<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

/**
 * Query Message Topic Container
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class MessageTopic
{
    /**
     * Pubsub topic implying execution was a success
     *
     * @const string
     */
    const SUCCESS = 'success';

    // Query Failure Topics

    /**
     * Pubsub topic implying query was a failure and caused by
     * implementation of fetchData() returning array incompatible with models
     *
     * @const string
     */
    const FAILURE_MODEL_HYDRATOR = 'failure.model_hydrator';

    /**
     * Pubsub topic implying query was a failure and caused by
     * implementation of fetchData() throwing an exception
     *
     * @const string
     */
    const FAILURE_DATA_FETCH = 'failure.data_fetch';

    /**
     * Pubsub topic implying execution was a failure and caused by
     * implementation of fetchData() returning non-array
     *
     * @const string
     */
    const FAILURE_DATA_TYPE = 'failure.data_type';

    // Query Fallback Topics

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() returning array incompatible with models
     *
     * @const string
     */
    const FALLBACK_MODEL_HYDRATOR = 'fallback.model_hydrator';

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() throwing an exception
     *
     * @const string
     */
    const FALLBACK_DATA_FETCH = 'fallback.data_fetch';

    /**
     * Pubsub topic implying query has fallen back to decorated instance due to
     * implementation of fetchData() returning non-array
     *
     * @const string
     */
    const FALLBACK_DATA_TYPE = 'fallback.data_type';
}

<?php

namespace Colt\Query;

/**
 * Colt Query Interface
 *
 * Read-only DAO for fetching Models from API, DB, cache or other storage.
 * Implements a decorator pattern for auto-failover functionality.
 * Publishes topic-filtered events to subcribers on execution.
 *
 * @category   Colt
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface QueryInterface
{
    /**
     * Returns the last model composite yeilded from query execution
     * 
     * @return ModelCompositeInterface
     */
    public function getLastResponse();
}

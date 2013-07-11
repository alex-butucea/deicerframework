<?php

namespace Colt\Query\Event;

/**
 * Marker interface for concrete Parametized Query Execution Event Builder
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParametizedExecutionEventBuilderInterface
{
    /**
     * Set parameter set to build with
     * 
     * @param  array $params Parameters to build with
     * @return ParametizedExecutionEventBuilderInterface Fluent interface
     */
    public function withParams(array $params);
}

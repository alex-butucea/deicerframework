<?php

namespace Deicer\Query\Event;

use Deicer\Stdlib\Pubsub\EventBuilderInterface;

/**
 * Marker interface for concrete Parameterized Query Event Builder
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParameterizedQueryEventBuilderInterface extends
 EventBuilderInterface
{
    /**
     * Set parameter set to build with
     * 
     * @param  array $params Parameters to build with
     * @return ParameterizedQueryEventBuilderInterface Fluent interface
     */
    public function withParams(array $params);
}

<?php

namespace Colt\Query\Event;

use Colt\Stdlib\Pubsub\EventBuilderInterface;

/**
 * Marker interface for concrete Tokenized Query Execution Event Builder
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenizedExecutionEventBuilderInterface extends
 EventBuilderInterface
{
    /**
     * Set unique token to build with
     * 
     * @throws NonStringException If $token is not a string
     * @param  string $token Token to build with
     * @return TokenizedExecutionEventBuilderInterface Fluent interface
     */
    public function withToken($token);
}

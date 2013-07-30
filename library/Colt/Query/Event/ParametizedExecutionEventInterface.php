<?php

namespace Colt\Query\Event;

use Colt\Query\ParametizedQueryInterface;
use Colt\Query\Event\ExecutionEventInterface;
use Colt\Stdlib\Pubsub\EventInterface;
use Colt\Stdlib\ParameterProviderInterface;

/**
 * Interface for execution events generated by parametized queries
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParametizedExecutionEventInterface extends
 ParameterProviderInterface,
 ExecutionEventInterface
{
    /**
     * Parametized query constructor
     *
     * @throws NonStringException If $topic is not a string
     * @param  string $topic See constants in ExecutionEventInterface
     * @param  mixed $content Content returned from the query's inner execution
     * @param  ParametizedQueryInterface $publisher Event originator
     * @param  array $params Parameters used in query execution
     * @return ParametizedExecutionEventInterface
     */
    public function __construct(
        $topic,
        $content,
        ParametizedQueryInterface $publisher,
        array $params
    );
}

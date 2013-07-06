<?php

namespace Colt\Stdlib;

/**
 * Interface for objects yielded from a call to an Executable object
 *
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExecutionInterface
{
    /**
     * Get the amount of time taken to execute
     *
     * @return mixed
     */
    public function getElapsedTime();

    /**
     * Add to the total time taken to execute
     *
     * @param  mixed $interval The time to add to the total elapsed time
     * @return void
     */
    public function addElapsedTime($interval);
}

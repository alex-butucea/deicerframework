<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Stdlib;

/**
 * Interface for objects yielded from a call to an Executable object
 *
 * @category   Deicer
 * @package    Stdlib
 * @subpackage Exe
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
     * @return int
     */
    public function getElapsedTime();

    /**
     * Add to the total time taken to execute
     *
     * @param  int $interval The time to add to the total elapsed time
     * @return void
     */
    public function addElapsedTime($interval);
}

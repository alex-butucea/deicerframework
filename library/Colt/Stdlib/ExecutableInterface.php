<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that can be executed to perform a focused task
 *
 * Api implies decoration capability to allow for extension by composition
 *
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExecutableInterface
{
    /**
     * Execute contained logic
     */
    public function execute();

    /**
     * Decorate an existing executable
     *
     * @param ExecutableInterface $decoratable The executable to decorate
     */
    public function decorate(ExecutableInterface $decoratable);
}

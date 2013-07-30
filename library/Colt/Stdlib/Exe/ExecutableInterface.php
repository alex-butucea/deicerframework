<?php

namespace Colt\Stdlib\Exe;

/**
 * Interface for objects that can be executed to perform a focused task
 *
 * Api implies decoration capability to allow for extension by composition
 *
 * @category   Colt
 * @package    Stdlib
 * @subpackage Exe
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExecutableInterface
{
    /**
     * Execute contained logic
     *
     * @return mixed
     */
    public function execute();
}

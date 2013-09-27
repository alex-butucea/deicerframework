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
 * Interface for objects that can be executed to perform a focused task
 *
 * Api implies decoration capability to allow for extension by composition
 *
 * @category   Deicer
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

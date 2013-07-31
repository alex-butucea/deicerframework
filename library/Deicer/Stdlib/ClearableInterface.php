<?php

namespace Deicer\Stdlib;

/**
 * Interface for objects that can discard their property values
 * 
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ClearableInterface
{
    /**
     * Discard property values
     *
     * @return void
     */
    public function clear();
}

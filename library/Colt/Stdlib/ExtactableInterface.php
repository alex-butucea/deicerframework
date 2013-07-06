<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that can have their properties extracted to an array
 * 
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExtactableInterface
{
    /**
     * Extract properties to array
     *
     * @return array
     */
    public function getArrayCopy();
}

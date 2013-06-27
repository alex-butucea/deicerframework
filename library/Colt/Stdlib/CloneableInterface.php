<?php

namespace Colt\Stdlib;

/**
 * Interface for cloneable objects that return deep copies
 * 
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface CloneableInterface
{
    /**
     * Perform deep object copy
     */
    public function __clone();
}

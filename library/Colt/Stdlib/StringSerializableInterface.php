<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that can self serialize to arbitrary strings
 *
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface StringSerializableInterface
{
    /**
     * Serialize to arbitrary string
     */
    public function __toString();
}

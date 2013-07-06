<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that can be populated by an array
 * 
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface HydratableInterface
{
    /**
     * Hydrate instance using an array
     *
     * @param  array $values Array to hydrate from
     * @return void
     */
    public function exchangeArray(array $values);
}

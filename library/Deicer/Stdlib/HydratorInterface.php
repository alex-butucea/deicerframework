<?php

namespace Deicer\Stdlib;

/**
 * Interface for objects capable of populating others using arrays
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface HydratorInterface
{
    /**
     * Hydrate internalised object instance using an array and return
     *
     * @param  array $values Array to hydrate from
     * @return HydratableInterface The hydrated object instance
     */
    public function exchangeArray(array $values);
}

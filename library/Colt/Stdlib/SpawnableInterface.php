<?php

namespace Colt\Stdlib;

/**
 * Interface for objects able to spawn new self instances
 * 
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface SpawnableInterface
{
    /**
     * Spawn new instance
     */
    public function spawn();
}

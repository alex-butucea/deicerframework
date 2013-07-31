<?php

namespace Deicer\Stdlib;

/**
 * Interface for objects that consume a uniquely identifiable token
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenConsumerInterface
{
    /**
     * Set unique token
     *
     * @param  mixed $token The token to set
     * @return void
     */
    public function setToken($token);
}

<?php

namespace Deicer\Stdlib;

/**
 * Interface for objects that consume a set of parameters
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParameterConsumerInterface
{
    /**
     * Set multiple parameters
     *
     * @param  array $params Key value pairs of params to set
     * @return void
     */
    public function setParams(array $params);

    /**
     * Set a single parameter
     *
     * @param  string $name  The name of the parameter to set
     * @param  mixed  $value The value to set
     * @return void
     */
    public function setParam($name, $value);
}

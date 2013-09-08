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
 * Interface for objects that provide a set of parameters
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParameterProviderInterface
{
    /**
     * Get all parameters
     *
     * @return array
     */
    public function getParams();

    /**
     * Get a single parameter by name
     *
     * @param  string $name Name of the parameter to retrieve
     * @return mixed
     */
    public function getParam($name);
}

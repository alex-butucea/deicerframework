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
 * Interface for objects that provide a set of attributes
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface AttributeProviderInterface
{
    /**
     * Get all attributes
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get a single attribute by name
     *
     * @throws InvalidArgumentException If $name is empty
     * @throws InvalidArgumentException If $name is a non string
     *
     * @param  string $name Name of the attribute to retrieve
     * @return scalar|null Attribute value or null if attribute doesnt exist
     */
    public function getAttribute($name);
}

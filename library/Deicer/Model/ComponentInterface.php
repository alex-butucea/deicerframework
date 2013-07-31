<?php

namespace Deicer\Model;

/**
 * Deicer Component Interface
 *
 * Provides a common interface for Abstract Models and Model Composites.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ComponentInterface
{
    /**
     * Component constructor
     *
     * @param  array $values Array to hydrate instance
     * @return ComponentInterface
     */
    public function __construct(array $values = null);

    /**
     * Hydrate instance using an array, skipping invalid values to prevent exceptions
     *
     * @param  array $values Array to hydrate from
     * @return ComponentInterface Fluent interface
     */
    public function tryExchangeArray(array $values);
}

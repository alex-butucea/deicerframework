<?php

namespace Colt\Model;

/**
 * Colt Model Interface
 *
 * Provides a common interface for all domain models.
 *
 * @category   Colt
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ModelInterface extends ComponentInterface
{
    /**
     * Prevents additional properties to be injected into instance at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __set($key, $value);

    /**
     * Throws exception when nonexistent property is unset at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __unset($key);

    /**
     * Throws exception when nonexistent property is accessed at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __get($key);
}

<?php

namespace Deicer\Model;

use Deicer\Stdlib\ClearableInterface;
use Deicer\Stdlib\ExtactableInterface;

/**
 * Deicer Model Interface
 *
 * Provides a common interface for all domain models.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ModelInterface extends
 ComponentInterface,
 ClearableInterface,
 ExtactableInterface
{
    /**
     * Prevents additional properties to be injected into instance at runtime
     *
     * @throws OutOfBoundsException
     * @return void
     */
    public function __set($key, $value);

    /**
     * Throws exception when nonexistent property is unset at runtime
     *
     * @throws OutOfBoundsException
     * @return void
     */
    public function __unset($key);

    /**
     * Throws exception when nonexistent property is accessed at runtime
     *
     * @throws OutOfBoundsException
     * @return void
     */
    public function __get($key);
}

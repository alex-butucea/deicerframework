<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Deicer\Stdlib\ClearableInterface;
use Deicer\Stdlib\ExtractableInterface;

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
 ExtractableInterface
{
    /**
     * Prevents additional properties to be injected into instance at runtime
     *
     * @throws NonExistentPropertyException
     * @return void
     */
    public function __set($key, $value);

    /**
     * Throws exception when nonexistent property is unset at runtime
     *
     * @throws NonExistentPropertyException
     * @return void
     */
    public function __unset($key);

    /**
     * Throws exception when nonexistent property is accessed at runtime
     *
     * @throws NonExistentPropertyException
     * @return void
     */
    public function __get($key);
}

<?php

namespace Colt\Model;

use Colt\Stdlib\ExtactableInterface;
use Colt\Exception\Type\NonArrayException;

/**
 * Colt Base Model
 *
 * Provides a common extension point for all domain models.
 *
 * @category   Colt
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractModel extends AbstractComponent implements
     ModelInterface,
     ExtactableInterface
{
    /**
     * Prevents additional properties to be injected into instance at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __set($key, $value)
    {
        throw new \OutOfBoundsException();
    }

    /**
     * Throws exception when nonexistent property is unset at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __unset($key)
    {
        throw new \OutOfBoundsException();
    }

    /**
     * Throws exception when nonexistent property is accessed at runtime
     *
     * @throws OutOfBoundsException
     */
    public function __get($key)
    {
        throw new \OutOfBoundsException();
    }

    /**
     * Extract fields to array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        $ret = array ();
        foreach (static::getFields() as $name) {
            $ret[$name] = $this->$name;
        }
        return $ret;
    }

    /**
     * Returns public property names
     * 
     * @return array
     */
    public static function getFields()
    {
        $calledClass = get_called_class();
        $getFields = function ($class)
        {
            return get_class_vars($class);
        };

        return array_keys($getFields($calledClass));
    }

    /**
     * Hydrates model instance using an array
     *
     * Filters data structure passed through onExchangeArray() for pre-processing.
     *
     * @throws NonArrayException If onExchangeArray returns non-array
     * @param  array $values Properties to hydrate instance with
     * @param  bool $skipInvalid Whether invalid model properties should be skipped
     *
     * @return void
     */
    protected function innerExchangeArray(array $values, $skipInvalid)
    {
        // Pass vars through onExchangeArray processor and validate
        $vars = $this->onExchangeArray($values);
        if (! is_array($vars)) {
            throw new NonArrayException();
        } elseif (empty($vars)) {
            return;
        }

        // Iterate through each property and internalise only public properties
        $fields = static::getFields();
        foreach ($vars as $key => $val) {
            if (! in_array($key, $fields) && (bool) $skipInvalid) {
                continue;
            }

            $this->$key = $val;
        }
    }
}

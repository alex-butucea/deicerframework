<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Deicer\Model\Exception\NonExistentPropertyException;
use Deicer\Model\Exception\UnexpectedValueException;

/**
 * Deicer Base Model
 *
 * Provides a common extension point for all domain models.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractModel extends AbstractComponent implements ModelInterface
{
    /**
     * Prevents additional properties to be injected into instance at runtime
     *
     * @throws NonExistentPropertyException
     */
    public function __set($key, $value)
    {
        throw new NonExistentPropertyException(
            'Setting of nonexistent property "' . $key . '" in: '.
            get_called_class()
        );
    }

    /**
     * Throws exception when nonexistent property is unset at runtime
     *
     * @throws NonExistentPropertyException
     */
    public function __unset($key)
    {
        throw new NonExistentPropertyException(
            'Unsetting of nonexistent property "' . $key . '" in: '.
            get_called_class()
        );
    }

    /**
     * Throws exception when nonexistent property is accessed at runtime
     *
     * @throws NonExistentPropertyException
     */
    public function __get($key)
    {
        throw new NonExistentPropertyException(
            'Accessing of nonexistent property "' . $key . '" in: ' .
            get_called_class()
        );
    }

    /**
     * Discard field values and reset to class defaults
     *
     * @return AbstractModel Fluent interface
     */
    public function clear()
    {
        foreach (self::getPublicProperties() as $key => $value) {
            $this->$key = $value;
        }

        return $this;
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
        return array_keys(self::getPublicProperties());
    }

    /**
     * Hydrates model instance using an array
     *
     * Filters data structure passed through onExchangeArray() for pre-processing.
     *
     * @throws UnexpectedValueException If onExchangeArray returns non-array
     * @param  array $values Properties to hydrate instance with
     * @param  bool $skipInvalid Whether invalid model properties should be skipped
     *
     * @return void
     */
    protected function innerExchangeArray(array $values, $skipInvalid)
    {
        // Pass vars through onExchangeArray processor and validate
        $vars = $this->onExchangeArray($values);
        if (!is_array($vars)) {
            $cls = get_called_class();
            throw new UnexpectedValueException(
                'Non-array returned from ' . $cls . '::onExchangeArray in: ' .
                $cls . '::' . __METHOD__
            );
        } elseif (empty($vars)) {
            return;
        }

        // Iterate through each property and internalise only public properties
        $fields = static::getFields();
        foreach ($vars as $key => $val) {
            if (!in_array($key, $fields) && (bool) $skipInvalid) {
                continue;
            }

            $this->$key = $val;
        }
    }

    /**
     * Returns an array of the class properties with default values
     *
     * @return array
     */
    protected static function getPublicProperties()
    {
        $getFields = function ($class) {
            return get_class_vars($class);
        };

        return $getFields(get_called_class());
    }
}

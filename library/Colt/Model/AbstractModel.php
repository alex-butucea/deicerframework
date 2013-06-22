<?php

namespace Colt\Model;

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
abstract class AbstractModel extends AbstractComponent implements ModelInterface
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

        // Iterate through each property and internalise
        foreach ($vars as $key => $val) {
            if (! property_exists($this, $key) && (bool) $skipInvalid) {
                continue;
            }

            $this->$key = $val;
        }
    }
}

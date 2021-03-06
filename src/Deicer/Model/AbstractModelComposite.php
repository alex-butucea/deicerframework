<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Deicer\Model\Exception\OutOfRangeException;
use Deicer\Model\Exception\InvalidArgumentException;
use Deicer\Model\Exception\UnexpectedValueException;

/**
 * Deicer Abstract Model Composite
 *
 * Represents a composite set of domain specific models.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractModelComposite extends AbstractComponent implements
     ModelCompositeInterface
{
    /**
     * The internalised set of models
     *
     * @var array Deicer\Model\ModelInterface
     */
    protected $models = array();

    /**
     * Discard internalised model set
     *
     * @return AbstractModel Fluent interface
     */
    public function clear()
    {
        $this->models = array ();
        return $this;
    }

    /**
     * Extract model set to array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->models;
    }

    /**
     * Returns the number of internalised models
     *
     * @see    Countable::count
     * @return integer
     */
    public function count()
    {
        return count($this->models);
    }

    /**
     * Returns model that is currently selected by the internal array pointer
     *
     * @see    Iterator::current
     * @throws OutOfRangeException If internal array pointer holds no model
     * @return Deicer\Model\ModelInterface
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfRangeException();
        }

        return $this->models[key($this->models)];
    }

    /**
     * Returns key of model currently selected by the internal array pointer
     *
     * @see    Iterator::key
     * @throws OutOfRangeException If internal array pointer holds no model
     * @return integer
     */
    public function key()
    {
        if (!$this->valid()) {
            throw new OutOfRangeException();
        }

        return key($this->models);
    }

    /**
     * Advances internal array pointer of internalised model set
     *
     * @see    Iterator::next
     * @return Deicer\Model\AbstractModelComposite Fluent interface
     */
    public function next()
    {
        next($this->models);
        return $this;
    }

    /**
     * Resets internal array pointer of internalised model set
     *
     * @see    Iterator::rewind
     * @return Deicer\Model\AbstractModelComposite Fluent interface
     */
    public function rewind()
    {
        reset($this->models);
        return $this;
    }

    /**
     * Assert whether internal array pointer is indexing a model instance
     *
     * @see    Iterator::valid
     * @return bool
     */
    public function valid()
    {
        $key = key($this->models);
        return isset($key);
    }

    /**
     * Asserts whether a particular model index exists
     *
     * @see    ArrayAccess::offsetExists
     * @throws InvalidArgumentException If $offset is not an integer
     * @param  integer $offset The offset to check for existence
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Non-int $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        }

        return isset($this->models[(integer) $offset]);
    }

    /**
     * Returns a model from the internalised set at a particualr index
     *
     * @see    ArrayAccess::offsetGet
     * @throws InvalidArgumentException If $offset is not an integer
     * @throws OutOfRangeException If no model exists at $offset
     * @param  integer $offset The offset to retrieve the model from
     *
     * @return ModelInterface
     */
    public function offsetGet($offset)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Non-int $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        } elseif (!$this->offsetExists($offset)) {
            throw new OutOfRangeException(
                'Nonexistent $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        }

        return $this->models[(integer) $offset];
    }

    /**
     * Sets a model instance at a particular index
     *
     * @see    ArrayAccess::offsetSet
     * @throws InvalidArgumentException If $offset is not an integer
     * @throws InvalidArgumentException If $value is not ModelInterface instance
     * @param  integer $offset The index to set
     * @param  Deicer\Model\ModelInterface $value The model instance to set
     *
     * @return Deicer\Model\AbstractModelComposite Fluent interface
     */
    public function offsetSet($offset, $value)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Non-int $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        } elseif (!$value instanceof ModelInterface) {
            throw new InvalidArgumentException(
                'Non-instance of ModelInterface $value passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        }

        $this->models[$offset] = $value;
        return $this;
    }

    /**
     * Clears a model from the internalised set at a particular index
     *
     * @see    ArrayAccess::offsetUnset
     * @throws InvalidArgumentException If $offset is not an integer
     * @throws OutOfRangeException If no model exists at $offset
     * @param  integer $offset The offset to remove the model instance from
     *
     * @return Deicer\Model\AbstractModelComposite Fluent interface
     */
    public function offsetUnset($offset)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Non-int $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        } elseif (!$this->offsetExists($offset)) {
            throw new OutOfRangeException(
                'Nonexistent $offset passed in: ' .
                get_called_class() . '::' . __METHOD__
            );
        }

        unset($this->models[(integer) $offset]);
        return $this;
    }

    /**
     * Hydrates composite instance using an array
     *
     * Filters model set passed through onExchangeArray() for pre-processing.
     *
     * @throws UnexpectedValueException If onExchangeArray returns non-array
     * @throws InvalidArgumentException If !$skipInvalid & $values has non ModelInterface
     * @param  array $values Models to hydrate instance with
     * @param  bool $skipInvalid Whether invalid values should be skipped
     *
     * @return void
     */
    protected function innerExchangeArray(array $values, $skipInvalid)
    {
        // Pass model set through onExchangeArray pre-processor and validate
        $set = $this->onExchangeArray($values);
        if (!is_array($set)) {
            $cls = get_called_class();
            throw new UnexpectedValueException(
                'Non-array returned from ' . $cls . '::onExchangeArray in: ' .
                $cls . '::' . __METHOD__
            );
        }

        // Iterate model set and accumulate if valid model instance
        $models = array ();
        foreach ($set as $model) {
            if (!$model instanceof ModelInterface) {
                if ((bool) $skipInvalid) {
                    continue;
                } else {
                    throw new InvalidArgumentException(
                        'Non-instance of ModelInterface in $values passed in: ' .
                        get_called_class() . '::' . __METHOD__
                    );
                }
            } else {
                $models[] = $model;
            }
        }

        // Internalise accumulated model set
        $this->models = $models;
    }
}

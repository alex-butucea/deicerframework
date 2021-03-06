<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Deicer\Stdlib\HydratableInterface;
use Deicer\Stdlib\CloneableInterface;
use Deicer\Stdlib\SpawnableInterface;

/**
 * Deicer Base Component
 *
 * Provides a common extension point for Abstract Models and Model Composites.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractComponent implements
     ComponentInterface,
     HydratableInterface,
     CloneableInterface,
     SpawnableInterface
{
    /**
     * Component constructor
     *
     * Immediately Calls onInstantiate() and hydrates instance with array provided
     *
     * @param  array $values Array to hydrate instance
     * @return Deicer\Model\AbstractComponent
     */
    public function __construct(array $values = null)
    {
        $this->onInstantiate();
        if ($values) {
            $this->exchangeArray($values);
        }
    }

    /**
     * Perform deep object copy
     *
     * @return Deicer\Model\AbstractComponent
     */
    public function __clone()
    {
        foreach ($this as $k => $v) {
            if (is_object($v)) {
                $this->$k = clone $v;
            }
        }
    }

    /**
     * Spawn new instance
     *
     * @return Deicer\Model\AbstractComponent
     */
    public function spawn()
    {
        return new static();
    }

    /**
     * Hydrate instance using an array
     *
     * @param  array $values Array to hydrate from
     * @return Deicer\Model\AbstractComponent Fluent interface
     */
    public function exchangeArray(array $values)
    {
        $this->innerExchangeArray($values, false);
        return $this;
    }

    /**
     * Hydrate instance using an array, skipping invalid values to prevent exceptions
     *
     * @param  array $values Array to hydrate from
     * @return Deicer\Model\AbstractComponent Fluent interface
     */
    public function tryExchangeArray(array $values)
    {
        $this->innerExchangeArray($values, true);
        return $this;
    }

    /**
     * Data passed to exchangeArray() is filtered through here for pre-processing
     *
     * @param  array $values The data structure passed to exchangeArray()
     * @return array
     */
    protected function onExchangeArray(array $values)
    {
        return $values;
    }

    /**
     * Hook called post instantiation
     * 
     * Implementation of this method by concrete components is reccommended 
     * as opposed to overriding class constructor as it keeps API consistent
     *
     * @return void
     */
    protected function onInstantiate()
    {
        return;
    }

    /**
     * Hydrates component instance using an array
     *
     * @param array $values Array to hydrate from
     * @param bool  $skipInvalid Whether invalid data should be skipped
     */
    abstract protected function innerExchangeArray(array $values, $skipInvalid);
}

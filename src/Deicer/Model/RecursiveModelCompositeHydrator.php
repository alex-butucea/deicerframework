<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Deicer\Model\Exception\OutOfBoundsException;
use Deicer\Model\Exception\InvalidArgumentException;
use Deicer\Model\ModelInterface;
use Deicer\Model\ModelCompositeInterface;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;

/**
 * Deicer Model Hydrator
 *
 * Uses arrays to hydrate composite model hierarchies
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class RecursiveModelCompositeHydrator implements
    RecursiveModelCompositeHydratorInterface
{
    /**
     * Model prototype used to populate composite
     *
     * @var ModelInterface
     */
    protected $modelPrototype;

    /**
     * Hydratable model composite instance
     *
     * @var ModelInterface
     */
    protected $modelComposite;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ModelInterface $modelPrototype,
        ModelCompositeInterface $modelComposite
    ) {
        $this->setModelPrototype($modelPrototype)->setModelComposite($modelComposite);
    }

    /**
     * {@inheritdoc}
     */
    public function setModelPrototype(ModelInterface $modelPrototype)
    {
        $this->modelPrototype = $modelPrototype;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setModelComposite(ModelCompositeInterface $modelComposite)
    {
        $this->modelComposite = $modelComposite;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Expects $values to be a numerically indexed array of associative arrays.
     * Attempts to hydrate Model if $values is an associative array.
     * Attempts to hydrate Model Composite if $values is an indexed array.
     *
     * @throws InvalidArgumentException If $values is empty
     * @throws InvalidArgumentException If $values is indexed and contains non array elements
     * @throws InvalidArgumentException If $values is indexed and elements are not associative
     * @throws InvalidArgumentException If $values elements dont match model properties
     */
    public function exchangeArray(array $values)
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Empty $values passed in: ' . __METHOD__);
        }

        // Assume composite if first int index is int, otherwise assume model
        return (is_int(key($values))) ?
            $this->hydrateModelComposite($values) :
            $this->hydrateModel($values);
    }

    /**
     * Clones model prototype and hydrates with given values 
     * 
     * @param  array $values Model properties to set
     * @return ModelInterface
     */
    protected function hydrateModel(array $values)
    {
        try {
            $model = clone $this->modelPrototype;
            $model->exchangeArray($values);
        } catch (OutOfBoundsException $e) {
            throw new InvalidArgumentException(
                '$values elements must match model properties in: ' .
                'RecursiveModelCompositeHydrator::exchangeArray',
                0,
                $e
            );
        }

        return $model;
    }

    /**
     * Clones model composite prototype and hydrates with sets of model properties
     * 
     * @param  array $values Array of model property arrays
     * @return ModelCompositeInterface
     */
    protected function hydrateModelComposite(array $values)
    {
        // Walk array validating indices and accumulating hydrated models
        $models = array ();
        foreach ($values as $key => $value) {
            if (!is_int($key)) {
                throw new InvalidArgumentException(
                    '$values must be numerically indexed in: ' .
                    'RecursiveModelCompositeHydrator::exchangeArray'
                );
            } elseif (!is_array($value)) {
                throw new InvalidArgumentException(
                    '$values must contain array elements in: ' .
                    'RecursiveModelCompositeHydrator::exchangeArray'
                );
            }

            // Clone model protoype, hydrate and accumulate
            $models[] = $this->hydrateModel($value);
        }

        $composite = clone $this->modelComposite;
        $composite->exchangeArray($models);
        return $composite;
    }
}

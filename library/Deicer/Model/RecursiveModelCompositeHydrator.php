<?php

namespace Deicer\Model;

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
     *
     * Expects $values to be a numerically indexed array of associative arrays
     *
     * @throws InvalidArgumentException If $values contains associative indices
     * @throws InvalidArgumentException If $values contains non array elements
     * @throws InvalidArgumentException If $values elements are not associative
     * @throws InvalidArgumentException If $values dont match model properties
     */
    public function exchangeArray(array $values)
    {
        $models = array ();

        foreach ($values as $key => $value) {
            if (! is_int($key)) {
                throw new \InvalidArgumentException(
                    '$values must be numerically indexed in: ' .
                    get_called_class()
                );
            } if (! is_array($value)) {
                throw new \InvalidArgumentException(
                    '$values must contain array elements in: ' .
                    get_called_class()
                );
            }

            foreach ($value as $k => $v) {
                if (is_int($k)) {
                    throw new \InvalidArgumentException(
                        '$values must contain assoc. arrays in: ' .
                        get_called_class()
                    );
                }
            }

            // Clone model protoype, hydrate and accumulate
            $model = clone $this->modelPrototype;
            try {
                $models[] = $model->exchangeArray($value);
            } catch (\OutOfBoundsException $e) {
                throw new \InvalidArgumentException(
                    '$values elements must match model properties in: ' .
                    get_called_class(),
                    0,
                    $e
                );
            }
        }

        $this->modelComposite->exchangeArray($models);
        return $this->modelComposite;
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
}

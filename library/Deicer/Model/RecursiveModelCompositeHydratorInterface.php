<?php

namespace Deicer\Model;

use Deicer\Model\ModelInterface;
use Deicer\Model\ModelCompositeInterface;
use Deicer\Stdlib\HydratorInterface;

/**
 * Interface for Recursive Deicer Model Composite Hydrator
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
interface RecursiveModelCompositeHydratorInterface extends HydratorInterface
{
    /**
     * Hydrator constructor
     *
     * Internalises model prototype and composite instances to be hydrated
     *
     * @param  ModelInterface $modelPrototype Model used to populate composite
     * @param  ModelCompositeInterface $modelComposite Composite to be hydrated
     * @return RecursiveModelCompositeHydratorInterface
     */
    public function __construct(
        ModelInterface $modelPrototype,
        ModelCompositeInterface $modelComposite
    );

    /**
     * Sets the model prototype that will be cloned to populate composite
     *
     * @param  ModelInterface $modelPrototype Model used to populate composite
     * @return RecursiveModelCompositeHydratorInterface Fluent interface
     */
    public function setModelPrototype(ModelInterface $modelPrototype);

    /**
     * Sets the model composite instance to be hydrated
     *
     * @param  ModelCompositeInterface $modelComposite Composite to be hydrated
     * @return RecursiveModelCompositeHydratorInterface Fluent interface
     */
    public function setModelComposite(ModelCompositeInterface $modelComposite);
}

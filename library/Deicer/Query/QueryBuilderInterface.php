<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Model\ModelInterface;
use Deicer\Model\ModelCompositeInterface;

/**
 * Deicer Query Builder Interface
 *
 * Assembles concrete query instances.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface QueryBuilderInterface
{
    /**
     * Query builder constructor
     * 
     * @throws NonStringException If $namespace is not a string
     * @param  string $namespace Namespace concrete queries are located under
     * @return QueryBuilderInterface
     */
    public function __construct($namespace);

    /**
     * Sets the data provider to build with
     * 
     * @param  mixed $dataProvider Data provider to build with
     * @return QueryBuilderInterface Fluent interface
     */
    public function withDataProvider($dataProvider);

    /**
     * Sets the model prototype to build with
     * 
     * @param  ModelInterface $modelPrototype Model prototype to build with
     * @return QueryBuilderInterface Fluent interface
     */
    public function withModelPrototype(ModelInterface $modelPrototype);

    /**
     * Sets the model composite prototype to build with
     * 
     * @param  ModelCompositeInterface $modelCompositePrototype
     *         Composite prototype to build with
     * @return QueryBuilderInterface Fluent interface
     */
    public function withModelCompositePrototype(
        ModelCompositeInterface $modelCompositePrototype
    );

    /**
     * Assembles an instance of the specified query using the set parameters
     * 
     * @throws LogicException If model prototype has not been set 
     * @throws LogicException If model composite prototype has not been set 
     * @throws InvalidArgumentException If $classname is empty
     * @throws NonStringException If $classname is not a string
     * @throws NonInstanceException If query doesnt implement QueryInterface
     * @throws NonExistentClassException If class doesn't exist
     * 
     * @param  string $classname Query name that is appended onto set namespace 
     * @return QueryInterface Assembled query instance
     */
    public function build($classname);
}

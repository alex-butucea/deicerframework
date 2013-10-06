<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\QueryBuilderInterface;
use Deicer\Model\ModelInterface;
use Deicer\Model\ModelCompositeInterface;
use Deicer\Model\RecursiveModelCompositeHydrator;
use Deicer\Pubsub\MessageBuilder;
use Deicer\Pubsub\UnfilteredMessageBroker;
use Deicer\Pubsub\TopicFilteredMessageBroker;
use Deicer\Exception\NonExistentClassException;

/**
 * {@inheritdoc}
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class QueryBuilder implements QueryBuilderInterface
{
    /**
     * Class namespace concrete queries are located under
     * 
     * @var string
     */
    protected $namespace;

    /**
     * Data provider to build with
     * 
     * @var mixed
     */
    protected $dataProvider;

    /**
     * Model prototype to build with
     * 
     * @var ModelInterface
     */
    protected $modelPrototype;

    /**
     * Model composite prototype to build with
     * 
     * @var ModelCompositeInterface
     */
    protected $modelCompositePrototype;

    /**
     * {@inheritdoc}
     */
    public function __construct($namespace)
    {
        if (! is_string($namespace)) {
            throw new \InvalidArgumentException(
                'Non-string $namespace passed in: ' . __METHOD__
            );
        }

        $this->namespace = '\\' . trim($namespace, '\\') . '\\';
    }

    /**
     * {@inheritdoc}
     */
    public function withDataProvider($dataProvider)
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withModelPrototype(ModelInterface $modelPrototype)
    {
        $this->modelPrototype = $modelPrototype;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withModelCompositePrototype(
        ModelCompositeInterface $modelCompositePrototype
    ) {
        $this->modelCompositePrototype = $modelCompositePrototype;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build($classname)
    {
        if (empty($classname)) {
            throw new \InvalidArgumentException(
                '$classname required for build in: ' . __METHOD__
            );
        } elseif (! $this->modelPrototype) {
            throw new \LogicException(
                'Model prototype required for build in: ' . __METHOD__
            );
        } elseif (! $this->modelCompositePrototype) {
            throw new \LogicException(
                'Model composite prototype required for build in: ' . __METHOD__
            );
        }

        if (! is_string($classname)) {
            throw new \InvalidArgumentException(
                'Non-string $classname passed in: ' . __METHOD__
            );
        }

        // Build absolute classname and validate
        $fullname = $this->namespace . $classname;
        try {
            $class = new \ReflectionClass($fullname);
        } catch (\ReflectionException $e) {
            throw new NonExistentClassException($e->getMessage(), 0, $e);
        }

        // Ensure query implements a valid interface
        $interfaces = $class->getInterfaces();
        if (!isset($interfaces['Deicer\Query\InvariableQueryInterface']) &&
            !isset($interfaces['Deicer\Query\TokenizedQueryInterface']) &&
            !isset($interfaces['Deicer\Query\ParameterizedQueryInterface'])
        ) {
            throw new \UnexpectedValueException(
                'Unexpected class interface in: ' . __METHOD__
            );
        }

        // Assemble and return query
        return new $fullname(
            $this->dataProvider,
            new MessageBuilder(),
            new UnfilteredMessageBroker(),
            new TopicFilteredMessageBroker(),
            new RecursiveModelCompositeHydrator(
                $this->modelPrototype,
                $this->modelCompositePrototype
            )
        );
    }
}

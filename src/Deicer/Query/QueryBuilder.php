<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use ReflectionClass;
use ReflectionException;
use Deicer\Query\Exception\LogicException;
use Deicer\Query\Exception\UnexpectedValueException;
use Deicer\Query\Exception\InvalidArgumentException;
use Deicer\Query\QueryBuilderInterface;
use Deicer\Model\ModelInterface;
use Deicer\Model\ModelCompositeInterface;
use Deicer\Model\RecursiveModelCompositeHydrator;
use Deicer\Pubsub\MessageBuilder;
use Deicer\Pubsub\UnfilteredMessageBroker;
use Deicer\Pubsub\TopicFilteredMessageBroker;
use Deicer\Query\Exception\NonExistentQueryException;

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
     * Lazy-loaded instance of MessageBuilder
     *
     * @var MessageBuilder
     */
    protected $messageBuilder;

    /**
     * Lazy-loaded instance of UnfilteredMessageBroker
     *
     * @var UnfilteredMessageBroker
     */
    protected $unfilteredMessageBroker;

    /**
     * Lazy-loaded instance of TopicFilteredMessageBroker
     *
     * @var TopicFilteredMessageBroker
     */
    protected $topicFilteredMessageBroker;

    /**
     * Lazy-loaded instance of RecursiveModelCompositeHydrator
     *
     * @var RecursiveModelCompositeHydrator
     */
    protected $modelHydrator;

    /**
     * {@inheritdoc}
     */
    public function __construct($namespace)
    {
        if (!is_string($namespace)) {
            throw new InvalidArgumentException(
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
            throw new InvalidArgumentException(
                '$classname required for build in: ' . __METHOD__
            );
        } elseif (!$this->modelPrototype) {
            throw new LogicException(
                'Model prototype required for build in: ' . __METHOD__
            );
        } elseif (!$this->modelCompositePrototype) {
            throw new LogicException(
                'Model composite prototype required for build in: ' . __METHOD__
            );
        }

        if (!is_string($classname)) {
            throw new InvalidArgumentException(
                'Non-string $classname passed in: ' . __METHOD__
            );
        }

        // Build absolute classname and validate
        $fullname = $this->namespace . $classname;
        try {
            $class = new ReflectionClass($fullname);
        } catch (ReflectionException $e) {
            throw new NonExistentQueryException($e->getMessage(), 0, $e);
        }

        // Ensure query implements a valid interface
        $interfaces = $class->getInterfaces();
        if (!isset($interfaces['Deicer\Query\InvariableQueryInterface']) &&
            !isset($interfaces['Deicer\Query\SlugizedQueryInterface']) &&
            !isset($interfaces['Deicer\Query\IdentifiedQueryInterface']) &&
            !isset($interfaces['Deicer\Query\ParameterizedQueryInterface'])
        ) {
            throw new UnexpectedValueException(
                'Unexpected class interface in: ' . __METHOD__
            );
        }

        // Assemble and return query
        return new $fullname(
            $this->dataProvider,
            $this->getMessageBuilder(),
            $this->getUnfilteredMessageBroker(),
            $this->getTopicFilteredMessageBroker(),
            $this->getModelHydrator()
                ->setModelPrototype($this->modelPrototype)
                ->setModelComposite($this->modelCompositePrototype)
        );
    }

    /**
     * Lazy-loads an instance of MessageBuilder
     *
     * @return MessageBuilder
     */
    protected function getMessageBuilder()
    {
        if (!$this->messageBuilder) {
            $this->messageBuilder = new MessageBuilder();
        }

        return $this->messageBuilder;
    }

    /**
     * Lazy-loads an instance of UnfilteredMessageBroker
     *
     * @return UnfilteredMessageBroker
     */
    protected function getUnfilteredMessageBroker()
    {
        if (!$this->unfilteredMessageBroker) {
            $this->unfilteredMessageBroker = new UnfilteredMessageBroker();
        }

        return $this->unfilteredMessageBroker;
    }

    /**
     * Lazy-loads an instance of TopicFilteredMessageBroker
     *
     * @return TopicFilteredMessageBroker
     */
    protected function getTopicFilteredMessageBroker()
    {
        if (!$this->topicFilteredMessageBroker) {
            $this->topicFilteredMessageBroker = new TopicFilteredMessageBroker();
        }

        return $this->topicFilteredMessageBroker;
    }

    /**
     * Lazy-loads an instance of RecursiveModelCompositeHydrator
     *
     * @return RecursiveModelCompositeHydrator
     */
    protected function getModelHydrator()
    {
        if (!$this->modelHydrator) {
            $this->modelHydrator = new RecursiveModelCompositeHydrator(
                $this->modelPrototype,
                $this->modelCompositePrototype
            );
        }

        return $this->modelHydrator;
    }
}

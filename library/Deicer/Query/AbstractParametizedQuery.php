<?php

namespace Deicer\Query;

use Deicer\Query\AbstractQuery;
use Deicer\Query\ParametizedQueryInterface;
use Deicer\Query\Event\ParametizedQueryEventBuilderInterface;
use Deicer\Query\Exception\NonExistentParamException;
use Deicer\Model\RecursiveModelCompositeHydratorInterface;
use Deicer\Exception\Type\NonStringException;

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
abstract class AbstractParametizedQuery extends AbstractQuery implements
     ParametizedQueryInterface
{
    /**
     * Key value pairs of parameters used to seed selection algorithm
     * 
     * @var array
     */
    protected $params = array ();

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $dataProvider,
        ParametizedQueryEventBuilderInterface $eventBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    ) {
        $this->dataProvider  = $dataProvider;
        $this->eventBuilder  = $eventBuilder;
        $this->modelHydrator = $modelHydrator;
        $this->lastResponse  = $modelHydrator->exchangeArray(array ());

        $this->syncEventBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(ParametizedQueryInterface $decoratable)
    {
        $this->decorated = $decoratable;
        $this->syncDecorated();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonExistentParamException If query doesn't contain params $params
     */
    public function setParams(array $params)
    {
        // Validate params to ensure atomic param state
        foreach ($params as $key => $value) {
            $this->validateParam($key, __FUNCTION__);
        }

        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonStringException If $name is not a string
     * @throws NonExistentParamException If query doesn't contain param $name
     */
    public function setParam($name, $value)
    {
        $this->validateParam($name, __FUNCTION__);
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function trySetParams(array $params)
    {
        $set = array ();
        foreach ($params as $key => $value) {
            if (array_key_exists($key, $this->params)) {
                $set[$key] = $value;
            }
        }

        $this->params = array_merge($this->params, $set);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonStringException If $name is not a string
     * @throws NonExistentParamException If query doesn't contain param $name
     */
    public function getParam($name)
    {
        $this->validateParam($name, __FUNCTION__);
        return $this->params[$name];
    }

    /**
     * {@inheritdoc}
     */
    protected function syncEventBuilder()
    {
        $this->eventBuilder->withParams($this->params);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function syncDecorated()
    {
        if ($this->decorated) {
            $this->decorated->trySetParams($this->params);
        }

        return $this;
    }

    /**
     * Throws exception if concrete implementation doesn't contain param passed
     * 
     * @throws NonStringException If $name is not a string
     * @throws NonExistentParamException If query doesn't contain param $param
     * @param  string $param The param to validate
     * @param  string $method The name of the method called
     * @return void
     */
    protected function validateParam($param, $method)
    {
        if (! is_string($param)) {
            throw new NonStringException(
                'Non string $param passed in: ' . $method
            );
        } elseif (! array_key_exists($param, $this->params)) {
            throw new NonExistentParamException(
                'Non existent parameter "' . $param . '" passed in: ' .
                get_called_class() . '::' . $method
            );
        }
    }
}

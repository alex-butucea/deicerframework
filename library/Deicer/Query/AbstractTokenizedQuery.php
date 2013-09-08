<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\AbstractQuery;
use Deicer\Query\TokenizedQueryInterface;
use Deicer\Query\Message\TokenizedQueryMessageBuilderInterface;
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
abstract class AbstractTokenizedQuery extends AbstractQuery implements
     TokenizedQueryInterface
{
    /**
     * Selection token
     * 
     * @var string
     */
    protected $token = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $dataProvider,
        TokenizedQueryMessageBuilderInterface $messageBuilder,
        RecursiveModelCompositeHydratorInterface $modelHydrator
    ) {
        $this->dataProvider  = $dataProvider;
        $this->messageBuilder  = $messageBuilder;
        $this->modelHydrator = $modelHydrator;
        $this->lastResponse  = $modelHydrator->exchangeArray(array ());

        $this->syncMessageBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function decorate(TokenizedQueryInterface $decoratable)
    {
        $this->decorated = $decoratable;
        $this->syncDecorated();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        if (! is_string($token)) {
            throw new NonStringException();
        }

        $this->token = $token;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    protected function syncMessageBuilder()
    {
        $this->messageBuilder->withToken($this->token);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function syncDecorated()
    {
        if ($this->decorated) {
            $this->decorated->setToken($this->token);
        }

        return $this;
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\Exception\InvalidArgumentException;
use Deicer\Query\AbstractQuery;
use Deicer\Query\TokenizedQueryInterface;

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
    public function decorate(TokenizedQueryInterface $decorable)
    {
        $this->decorated = $decorable;
        $this->syncDecorated();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException(
                'Non-string $token passed in: ' .
                get_called_class() . '::' . __FUNCTION__
            );
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
    protected function syncDecorated()
    {
        if ($this->decorated) {
            $this->decorated->setToken($this->token);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupplementaryMessageAttributes()
    {
        return array ('token' => $this->token);
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Message;

use Deicer\Query\Message\ParameterizedQueryMessage;
use Deicer\Query\Message\ParameterizedQueryMessageBuilderInterface;
use Deicer\Pubsub\AbstractMessageBuilder;

/**
 * Assembles instances of Parameterized Query Messages
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryMessageBuilder extends AbstractMessageBuilder implements
    ParameterizedQueryMessageBuilderInterface
{
    /**
     * Parameter set to build with
     * 
     * @var array
     */
    protected $params;

    /**
     * {@inheritdoc}
     */
    public function withParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws LogicException If topic is empty
     * @throws LogicException If content has not been set
     * @throws LogicException If publisher has not been set
     * @throws LogicException If params has not been set
     */
    public function build()
    {
        if (empty($this->topic)) {
            throw new \LogicException(
                'Topic required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->content)) {
            throw new \LogicException(
                'Content required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->publisher)) {
            throw new \LogicException(
                'Publisher required for build in: ' . __METHOD__
            );
        } elseif (! isset($this->params)) {
            throw new \LogicException(
                'Params required for build in: ' . __METHOD__
            );
        }

        return new ParameterizedQueryMessage(
            $this->topic,
            $this->content,
            $this->publisher,
            $this->params
        );
    }
}

<?php

namespace Deicer\Query\Event;

use Deicer\Query\Event\ParametizedQueryEvent;
use Deicer\Query\Event\ParametizedQueryEventBuilderInterface;
use Deicer\Stdlib\Pubsub\AbstractEventBuilder;

/**
 * Assembles instances of Parametized Query Events
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedQueryEventBuilder extends AbstractEventBuilder implements
    ParametizedQueryEventBuilderInterface
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

        return new ParametizedQueryEvent(
            $this->topic,
            $this->content,
            $this->publisher,
            $this->params
        );
    }
}

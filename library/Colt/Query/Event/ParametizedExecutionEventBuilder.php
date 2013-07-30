<?php

namespace Colt\Query\Event;

use Colt\Query\Event\ParametizedExecutionEvent;
use Colt\Query\Event\ParametizedExecutionEventBuilderInterface;
use Colt\Stdlib\Pubsub\AbstractEventBuilder;

/**
 * Assembles instances of Parametized Query Execution Events
 *
 * @category   Colt
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedExecutionEventBuilder extends AbstractEventBuilder implements
    ParametizedExecutionEventBuilderInterface
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
            throw new \LogicException('Topic required for build in: ' . __METHOD__);
        } if (! isset($this->content)) {
            throw new \LogicException('Content required for build in: ' . __METHOD__);
        } if (! isset($this->publisher)) {
            throw new \LogicException('Publisher required for build in: ' . __METHOD__);
        } if (! isset($this->params)) {
            throw new \LogicException('Params required for build in: ' . __METHOD__);
        }

        return new ParametizedExecutionEvent(
            $this->topic,
            $this->content,
            $this->publisher,
            $this->params
        );
    }
}

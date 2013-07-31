<?php

namespace Deicer\Stdlib\Pubsub;

use Deicer\Stdlib\Pubsub\EventBuilderInterface;
use Deicer\Exception\Type\NonStringException;

/**
 * Abstract class for concrete builders that assemble pubsub events
 *
 * @category   Deicer
 * @package    Stdlib
 * @subpackage Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractEventBuilder implements EventBuilderInterface
{
    /**
     * The event topic to build with
     *
     * @param string
     */
    protected $topic = '';

    /**
     * The content payload to build with
     *
     * @var mixed
     */
    protected $content;

    /**
     * Event publisher to build with
     *
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * {@inheritdoc}
     *
     * @throws NonStringException If $topic is a non string
     */
    public function withTopic($topic)
    {
        if (! is_string($topic)) {
            throw new NonStringException('String topic required in: ' . __METHOD__);
        }

        $this->topic = $topic;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withPublisher(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
        return $this;
    }
}

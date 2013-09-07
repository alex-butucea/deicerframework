<?php

namespace Deicer\Pubsub;

/**
 * Interface for builders that assemble events to be raised by publishers
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface EventBuilderInterface
{
    /**
     * Set the event topic
     *
     * @param  string $topic Topic to build with
     * @return EventBuilderInterface Fluent interface
     */
    public function withTopic($topic);

    /**
     * Set the content payload
     *
     * @param  mixed $content Content payload to build with
     * @return EventBuilderInterface Fluent interface
     */
    public function withContent($content);

    /**
     * Set the event publisher
     *
     * @param  PublisherInterface $publisher Publisher to build with
     * @return EventBuilderInterface Fluent interface
     */
    public function withPublisher(PublisherInterface $publisher);

    /**
     * Assemble event instance using internalised properties
     * 
     * @return EventInterface
     */
    public function build();
}

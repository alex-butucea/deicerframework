<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

/**
 * Interface for builders that assemble messages to be raised by publishers
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface MessageBuilderInterface
{
    /**
     * Set the message topic
     *
     * @throws InvalidArgumentException If $topic is empty
     * @throws InvalidArgumentException If $topic is a non string
     *
     * @param  string $topic Topic to build with
     * @return MessageBuilderInterface Fluent interface
     */
    public function withTopic($topic);

    /**
     * Set the content payload
     *
     * @param  mixed $content Content payload to build with
     * @return MessageBuilderInterface Fluent interface
     */
    public function withContent($content);

    /**
     * Set the message publisher
     *
     * @param  PublisherInterface $publisher Publisher to build with
     * @return MessageBuilderInterface Fluent interface
     */
    public function withPublisher(PublisherInterface $publisher);


    /**
     * Set the message attributes
     *
     * @throws InvalidArgumentException If $attributes contains non-string key
     * @throws InvalidArgumentException If $attributes contains non-null/scalar value
     *
     * @param  array $attributes
     * @return MessageBuilderInterface Fluent interface
     */
    public function withAttributes(array $attributes);

    /**
     * Assemble message instance using internalised properties
     * 
     * @throws LogicException If topic is empty
     * @throws LogicException If publisher has not been set
     *
     * @return MessageInterface
     */
    public function build();
}

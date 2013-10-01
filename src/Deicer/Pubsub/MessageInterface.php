<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Stdlib\AttributeProviderInterface;
use Deicer\Stdlib\StringSerializableInterface;

/**
 * Interface for messages raised by a publisher object
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface MessageInterface extends
 AttributeProviderInterface,
 StringSerializableInterface
{
    /**
     * Get the message topic
     *
     * @return string
     */
    public function getTopic();

    /**
     * Get the content payload
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Get the message publisher
     *
     * @return PublisherInterface
     */
    public function getPublisher();

    /**
     * Message constructor
     *
     * @throws InvalidArgumentException If $topic is empty
     * @throws InvalidArgumentException If $topic is a non-string
     * @throws InvalidArgumentException If $attributes contains non-string key
     * @throws InvalidArgumentException If $attributes contains non-null/scalar value
     *
     * @param  string $topic Topic to set
     * @param  mixed $content Content to set
     * @param  PublisherInterface $publisher Message originator
     * @param  array $attributes Supplementary attributes to set
     *
     * @return MessageInterface
     */
    public function __construct(
        $topic,
        $content,
        PublisherInterface $publisher,
        array $attributes = array ()
    );
}

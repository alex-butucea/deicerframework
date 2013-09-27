<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

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
interface MessageInterface extends StringSerializableInterface
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
}

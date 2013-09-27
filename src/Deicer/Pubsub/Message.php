<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\MessageInterface;
use Deicer\Pubsub\PublisherInterface;
use Deicer\Stdlib\StringSerializableInterface;
use Deicer\Exception\Type\NonStringException;

/**
 * Pubsub Message
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class Message implements MessageInterface
{
    /**
     * The message topic
     *
     * @param string
     */
    protected $topic = '';

    /**
     * The content payload
     *
     * @var mixed
     */
    protected $content;

    /**
     * Message publisher
     *
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * {@inheritdoc}
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     *
     * @return PublisherInterface
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $topic,
        $content,
        PublisherInterface $publisher
    ) {
        if (! is_string($topic)) {
            throw new NonStringException();
        }

        $this->topic = $topic;
        $this->content = $content;
        $this->publisher = $publisher;
    }

    /**
     * Retruns topic, json-encoded content and publisher classname
     *
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            'Topic: "%s" | Content: %s | Publisher: %s',
            $this->topic,
            json_encode($this->content),
            get_class($this->publisher)
        );
    }
}

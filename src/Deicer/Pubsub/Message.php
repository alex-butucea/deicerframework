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
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(
                'Non-string $name passed in: ' . __METHOD__
            );
        } elseif (empty($name)) {
            throw new \InvalidArgumentException(
                'Empty $name passed in: ' . __METHOD__
            );
        } 

        return (array_key_exists($name, $this->attributes)) ?
            $this->attributes[$name] :
            null;
    }

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $topic,
        $content,
        PublisherInterface $publisher,
        array $attributes = array ()
    ) {
        if (empty($topic)) {
            throw new \InvalidArgumentException(
                'Empty $topic passed in: ' . __METHOD__
            );
        } elseif (!is_string($topic)) {
            throw new \InvalidArgumentException(
                'Non-string $topic passed in: ' . __METHOD__
            );
        }

        // Ensure attribute names are strings and values are null/scalar
        foreach ($attributes as $key => $value) {
            if (empty($key)) {
                throw new \InvalidArgumentException(
                    'Empty key in $attributes passed in: ' .
                    __METHOD__
                );
            } elseif (!is_string($key)) {
                throw new \InvalidArgumentException(
                    'Non-int key in $attributes passed in: ' .
                    __METHOD__
                );
            } elseif (!is_null($value) && !is_scalar($value)) {
                throw new \InvalidArgumentException(
                    'Non-null/non-scalar value in $attributes passed in: ' .
                    __METHOD__
                );
            }
        }

        $this->topic      = $topic;
        $this->content    = $content;
        $this->publisher  = $publisher;
        $this->attributes = $attributes;
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

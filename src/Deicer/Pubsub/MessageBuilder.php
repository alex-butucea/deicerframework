<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\Exception\LogicException;
use Deicer\Pubsub\Exception\InvalidArgumentException;
use Deicer\Pubsub\Message;
use Deicer\Pubsub\MessageBuilderInterface;

/**
 * Assembles Pubsub Messages
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class MessageBuilder implements MessageBuilderInterface
{
    /**
     * The message topic to build with
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
     * Message publisher to build with
     *
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * Attributes to build with
     *
     * @var array
     */
    protected $attributes = array ();

    /**
     * {@inheritdoc}
     */
    public function withTopic($topic)
    {
        if (!is_string($topic)) {
            throw new InvalidArgumentException(
                'String topic required in: ' . __METHOD__
            );
        } elseif (empty($topic)) {
            throw new InvalidArgumentException(
                'Non-empty topic required in: ' . __METHOD__
            );
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

    /**
     * {@inheritdoc}
     */
    public function withAttributes(array $attributes)
    {
        // Ensure attribute names are strings and values are null/scalar
        foreach ($attributes as $key => $value) {
            if (empty($key)) {
                throw new InvalidArgumentException(
                    'Empty key in $attributes passed in: ' .
                    __METHOD__
                );
            } elseif (!is_string($key)) {
                throw new InvalidArgumentException(
                    'Non-int key in $attributes passed in: ' .
                    __METHOD__
                );
            } elseif (!is_null($value) && !is_scalar($value)) {
                throw new InvalidArgumentException(
                    'Non-null/non-scalar value in $attributes passed in: ' .
                    __METHOD__
                );
            }
        }

        $this->attributes = $attributes;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if (empty($this->topic)) {
            throw new LogicException(
                'Topic required for build in: ' . __METHOD__
            );
        } elseif (!$this->publisher) {
            throw new LogicException(
                'Publisher required for build in: ' . __METHOD__
            );
        }

        return new Message(
            $this->topic,
            $this->content,
            $this->publisher,
            $this->attributes
        );
    }
}

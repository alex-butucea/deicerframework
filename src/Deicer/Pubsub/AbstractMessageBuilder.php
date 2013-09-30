<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\MessageBuilderInterface;
use Deicer\Exception\Type\NonStringException;

/**
 * Abstract class for concrete builders that assemble pubsub messages
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractMessageBuilder implements MessageBuilderInterface
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
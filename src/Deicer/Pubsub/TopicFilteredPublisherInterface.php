<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\PublisherInterface;

/**
 * Topic Filtered Pubsub Message Publisher
 *
 * Subscribers register themselves with broker to receive messages of select topics
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TopicFilteredPublisherInterface extends PublisherInterface
{
    /**
     * Returns the topic filtered message broker for subscribers
     * 
     * @return TopicFilteredMessageBrokerInterface
     */
    public function getTopicFilteredMessageBroker();
}

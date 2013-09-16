<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub;

use Deicer\Pubsub\AbstractMessageBroker;
use Deicer\Pubsub\UnfilteredMessageBrokerInterface;

/**
 * Message broker that delivers all events intructed to publish
 *
 * @category   Deicer
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class UnfilteredMessageBroker extends AbstractMessageBroker implements
    UnfilteredMessageBrokerInterface
{
    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message)
    {
        foreach ($this->subscribers as $sub) {
            $sub->update($message);
        }

        return $this;
    }
}

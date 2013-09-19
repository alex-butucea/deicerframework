<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

use Deicer\Pubsub\UnfilteredMessageBroker;
use DeicerTest\Pubsub\AbstractMessageBrokerTest;

/**
 * Deicer Unfiltered Message Broker test suite
 *
 * @category   DeicerTest
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class UnfilteredMessageBrokerTest extends AbstractMessageBrokerTest
{
    public function setUp()
    {
        $this->fixture = new UnfilteredMessageBroker();
        parent::setUp();
    }

    public function testAddSubscriberEnsuresMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscriber($this->subscribers[0]);
        $this->fixture->addSubscriber($this->subscribers[1]);
        $this->fixture->addSubscriber($this->subscribers[2]);
        $this->fixture->publish($this->message);
    }

    public function testAddSubscribersEnsuresMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->publish($this->message);
    }

    public function testRemoveSubscriberEnsuresNoMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->removeSubscriber(1);
        $this->fixture->publish($this->message);
    }

    public function testRemoveSubscribersEnsuresNoMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->never())->method('update');

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->removeSubscribers(array (1, 2));
        $this->fixture->publish($this->message);
    }
}

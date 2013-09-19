<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

use Deicer\Pubsub\TopicFilteredMessageBroker;
use DeicerTest\Pubsub\AbstractMessageBrokerTest;

/**
 * Deicer Topic Filtered Message Broker test suite
 *
 * @category   DeicerTest
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TopicFilteredMessageBrokerTest extends AbstractMessageBrokerTest
{
    public function setUp()
    {
        $this->fixture = new TopicFilteredMessageBroker();
        parent::setUp();
    }

    public function testRemoveSubscribersEnsuresNoMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->never())->method('update');

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->removeSubscribers(array (1, 2));
        $this->fixture->publish($this->message);
    }

    public function testRemoveSubscribersAlsoRemovesTopicFilters()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->never())->method('update');

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->removeSubscribers(array (1, 2));
        $this->fixture->addSubscriber($this->subscribers[1]);
        $this->fixture->addSubscriber($this->subscribers[2]);
        $this->fixture->publish($this->message);
    }

    public function testRemoveSubscriberEnsuresNoMessageDelivery()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->removeSubscriber(1);
        $this->fixture->publish($this->message);
    }

    public function testRemoveSubscriberAlsoRemovesTopicFilters()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->once())->method('update')->with($this->message);
        $this->subscriber->expects($this->never())->method('update');

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->removeSubscriber(2);
        $this->fixture->addSubscriber($this->subscriber);
        $this->fixture->publish($this->message);
    }

    public function testPublishWithNoFiltersDistributesNoMessages()
    {
        $this->subscribers[0]->expects($this->never())->method('update');
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->never())->method('update');

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->publish($this->message);
    }

    public function testSubscribeToTopicImplementsFluentInterface()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $actual = $this->fixture->subscribeToTopic(0, 'foo');
        $this->assertSame($actual, $this->fixture);
    }

    public function testSubscribeToTopicWithNonStringTopicThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->subscribeToTopic(0, new \stdClass());
    }

    public function testSubscribeToTopicWithNonStringTopicArrayThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->subscribeToTopic(0, array ('foo', array (), 'bar'));
    }

    public function testSubscribeToTopicWithEmptyTopicThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->subscribeToTopic(0, '');
    }

    public function testSubscribeToTopicWithNonExistentSubscriberIndexThrowsException()
    {
        $this->setExpectedException('OutOfRangeException');
        $this->fixture->unsubscribeFromTopic(0, 'foo');
    }

    public function testSubscribeToTopicWithSingleTopicSubscribesSubscriberToTopicOnce()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobaz');
        $this->fixture->subscribeToTopic(2, 'qux');
        $this->fixture->subscribeToTopic(2, 'foobar');

        $this->fixture->publish($this->message);
    }

    public function testSubscribeToTopicWithTopicArraySubscribesSubscriberToUniqueTopicsOnce()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, array ('foobar', 'foobar'));
        $this->fixture->subscribeToTopic(1, array ('foobaz'));
        $this->fixture->subscribeToTopic(2, array ('qux', 'baz'));
        $this->fixture->subscribeToTopic(2, array ('foo'));
        $this->fixture->subscribeToTopic(2, array ('foo', 'foobar'));

        $this->fixture->publish($this->message);
    }

    public function testUnsubscribeFromTopicImplementsFluentInterface()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $actual = $this->fixture->unsubscribeFromTopic(0, 'foo');
        $this->assertSame($actual, $this->fixture);
    }

    public function testUnsubscribeFromTopicWithNonStringTopicThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->unsubscribeFromTopic(0, new \stdClass());
    }

    public function testUnsubscribeFromTopicWithNonStringTopicArrayThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->unsubscribeFromTopic(0, array ('foo', array (), 'bar'));
    }

    public function testUnsubscribeFromTopicWithEmptyTopicThrowsException()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->unsubscribeFromTopic(0, '');
    }

    public function testUnsubscribeFromTopicWithNonExistentSubscriberIndexThrowsException()
    {
        $this->setExpectedException('OutOfRangeException');
        $this->fixture->unsubscribeFromTopic(0, 'foo');
    }

    public function testUnsubscribeFromTopicWithSingleTopicUnsubscribesSubscriberFromTopic()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobaz');
        $this->fixture->subscribeToTopic(2, 'qux');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->unsubscribeFromTopic(1, 'foobar');
        $this->fixture->unsubscribeFromTopic(2, 'qux');

        $this->fixture->publish($this->message);
    }

    public function testUnsubscribeFromTopicWithTopicArrayUnsubscribesSubscriberFromTopics()
    {
        $this->subscribers[0]->expects($this->once())->method('update')->with($this->message);
        $this->subscribers[1]->expects($this->never())->method('update');
        $this->subscribers[2]->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers($this->subscribers);
        $this->fixture->subscribeToTopic(0, 'foobar');
        $this->fixture->subscribeToTopic(1, 'foobar');
        $this->fixture->subscribeToTopic(2, 'foobar');
        $this->fixture->unsubscribeFromTopic(0, array ('foo', 'bar'));
        $this->fixture->unsubscribeFromTopic(1, array ('foobar', 'foobaz'));
        $this->fixture->unsubscribeFromTopic(2, array ('qux'));

        $this->fixture->publish($this->message);
    }
}

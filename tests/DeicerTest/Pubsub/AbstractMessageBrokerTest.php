<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

use DeicerTest\Framework\TestCase;

/**
 * Common Message Broker tests
 *
 * @category   DeicerTest
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractMessageBrokerTest extends TestCase
{
    public $fixture;
    public $message;
    public $publisher;
    public $subscriber;
    public $subscribers;

    public function setUp()
    {
        $this->message     = $this->getMock('Deicer\Pubsub\MessageInterface');
        $this->publisher   = $this->getMock('Deicer\Pubsub\PublisherInterface');
        $this->subscriber  = $this->getMock('Deicer\Pubsub\SubscriberInterface');
        $this->subscribers = array (
            $this->getMock('Deicer\Pubsub\SubscriberInterface'),
            $this->getMock('Deicer\Pubsub\SubscriberInterface'),
            $this->getMock('Deicer\Pubsub\SubscriberInterface'),
        );

        $this->message
            ->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foobar'));
    }

    public function testAddSubscribersWithNonSubscriberInterfaceThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\InvalidArgumentException');
        $this->fixture->addSubscribers(
            array (
                $this->subscribers[0],
                'foo',
                $this->subscribers[2],
            )
        );
    }

    public function testAddSubscribersWithEmptyArrayThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\LengthException');
        $this->fixture->addSubscribers(array ());
    }

    public function testAddSubscribersPopulatesSubscriberPool()
    {
        $this->fixture->addSubscribers($this->subscribers);
        $this->assertSame(3, $this->fixture->addSubscriber($this->subscriber));
    }

    public function testAddSubscribersReturnsSubscribersReIndexed()
    {
        $actual = $this->fixture->addSubscribers($this->subscribers);

        $this->assertSame($this->subscribers[0], $actual[0]);
        $this->assertSame($this->subscribers[1], $actual[1]);
        $this->assertSame($this->subscribers[2], $actual[2]);
    }

    public function testAddSubscriberAutoIncrementsSubscriberIndex()
    {
        $this->assertSame(0, $this->fixture->addSubscriber($this->subscriber));
        $this->assertSame(1, $this->fixture->addSubscriber($this->subscriber));
        $this->assertSame(2, $this->fixture->addSubscriber($this->subscriber));
    }

    public function testAddSubscriberIndexAutoIncrementsFromHighestIndex()
    {
        $this->assertSame(0, $this->fixture->addSubscriber($this->subscriber));
        $this->fixture->removeSubscriber(0);
        $this->assertSame(0, $this->fixture->addSubscriber($this->subscriber));
        $this->assertSame(1, $this->fixture->addSubscriber($this->subscriber));
        $this->assertSame(2, $this->fixture->addSubscriber($this->subscriber));
        $this->fixture->removeSubscriber(1);
        $this->assertSame(3, $this->fixture->addSubscriber($this->subscriber));
    }

    public function testRemoveSubscribersImplementsFluentInterface()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $actual = $this->fixture->removeSubscriber(0);
        $this->assertSame($actual, $this->fixture);
    }

    public function testRemoveSubscribersWithNonIntIndexThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\InvalidArgumentException');
        $this->fixture->removeSubscribers(array ('foo'));
    }

    public function testRemoveSubscribersWithNonExistentIndexThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\OutOfRangeException');
        $this->fixture->removeSubscribers(array (2));
    }

    public function testRemoveSubscriberImplementsFluentInterface()
    {
        $this->fixture->addSubscriber($this->subscriber);
        $actual = $this->fixture->removeSubscriber(0);
        $this->assertSame($actual, $this->fixture);
    }

    public function testRemoveSubscriberWithNonIntIndexThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\InvalidArgumentException');
        $this->fixture->removeSubscriber('foo');
    }

    public function testRemoveSubscriberWithNonExistentIndexThrowsException()
    {
        $this->setExpectedException('Deicer\Pubsub\Exception\OutOfRangeException');
        $this->fixture->removeSubscriber(2);
    }

    public function testPublishImplementsFluentInterface()
    {
        $actual = $this->fixture->publish($this->message);
        $this->assertSame($actual, $this->fixture);
    }
}

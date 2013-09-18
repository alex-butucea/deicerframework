<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

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
abstract class AbstractMessageBrokerTest extends \PHPUnit_Framework_TestCase
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
        $this->setExpectedException('InvalidArgumentException');
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
        $this->setExpectedException('LengthException');
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

    public function testRemoveSubscriberImplementsFluentInterface()
    {
        $index  = $this->fixture->addSubscriber($this->subscriber);
        $actual = $this->fixture->removeSubscriber($index);
        $this->assertSame($actual, $this->fixture);
    }

    public function testRemoveSubscriberWithNonIntIndexThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->removeSubscriber('foo');
    }

    public function testRemoveSubscriberWithNonExistentIndexThrowsException()
    {
        $this->setExpectedException('OutOfRangeException');
        $this->fixture->removeSubscriber(2);
    }

    public function testPublishImplementsFluentInterface()
    {
        $actual = $this->fixture->publish($this->message);
        $this->assertSame($actual, $this->fixture);
    }
}

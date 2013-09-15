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
class UnfilteredMessageBrokerTest extends \PHPUnit_Framework_TestCase
{
    public $fixture;
    public $message;
    public $publisher;
    public $subscriber;

    public function setUp()
    {
        $this->fixture    = new UnfilteredMessageBroker();
        $this->message    = $this->getMock('Deicer\Pubsub\MessageInterface');
        $this->publisher  = $this->getMock('Deicer\Pubsub\PublisherInterface');
        $this->subscriber = $this->getMock('Deicer\Pubsub\SubscriberInterface');
    }

    public function testAddSubscribersWithNonSubscriberInterfaceThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->addSubscribers(
            array (
                $this->getMock('Deicer\Pubsub\SubscriberInterface'),
                'foo',
                $this->getMock('Deicer\Pubsub\SubscriberInterface'),
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
        $this->fixture->addSubscribers(
            array (
                $this->getMock('Deicer\Pubsub\SubscriberInterface'),
                $this->getMock('Deicer\Pubsub\SubscriberInterface'),
                $this->getMock('Deicer\Pubsub\SubscriberInterface'),
            )
        );
        $this->assertSame(3, $this->fixture->addSubscriber($this->subscriber));
    }

    public function testAddSubscribersReturnsSubscribersReIndexed()
    {
        $foo = $this->getMock('Deicer\Pubsub\SubscriberInterface');
        $bar = $this->getMock('Deicer\Pubsub\SubscriberInterface');
        $baz = $this->getMock('Deicer\Pubsub\SubscriberInterface');

        $actual = $this->fixture->addSubscribers(array ($foo, $bar, $baz));

        $this->assertSame($foo, $actual[0]);
        $this->assertSame($bar, $actual[1]);
        $this->assertSame($baz, $actual[2]);
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

    public function testPublishDistributesCorrectMessage()
    {
        $foo = $this->getMock('Deicer\Pubsub\SubscriberInterface');
        $bar = $this->getMock('Deicer\Pubsub\SubscriberInterface');
        $baz = $this->getMock('Deicer\Pubsub\SubscriberInterface');

        $foo->expects($this->once())->method('update')->with($this->message);
        $bar->expects($this->once())->method('update')->with($this->message);
        $baz->expects($this->once())->method('update')->with($this->message);

        $this->fixture->addSubscribers(array ($foo, $bar, $baz));
        $this->fixture->publish($this->message);
    }
}

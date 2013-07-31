<?php

namespace DeicerTest\Query;

use Deicer\Query\Exception\DataTypeException;
use Deicer\Query\Exception\DataFetchException;
use Deicer\Query\Exception\ModelHydratorException;
use DeicerTest\Query\TestableInvariableQueryWithValidFetchData;
use DeicerTest\Query\TestableInvariableQueryWithExceptionThrowingFetchData;

/**
 * Deicer Invariable Query unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class AbstractInvariableQueryTest extends \PHPUnit_Framework_TestCase
{
    public $fixture;
    public $composite;
    public $hydrator;
    public $event;
    public $eventBuilder;
    public $subscriber;

    public function setUp()
    {
        $this->eventBuilder = $this->getMock(
            'Deicer\Query\Event\InvariableQueryEventBuilderInterface'
        );
        $this->composite = $this->getMock(
            'Deicer\Model\ModelCompositeInterface'
        );
        $this->hydrator = $this->getMock(
            'Deicer\Model\RecursiveModelCompositeHydratorInterface'
        );
        $this->event = $this->getMock(
            'Deicer\Query\Event\InvariableQueryEventInterface'
        );
        $this->subscriber = $this->getMock(
            'Deicer\Stdlib\Pubsub\SubscriberInterface'
        );

        $this->event
            ->expects($this->any())
            ->method('getPublisher')
            ->will($this->returnValue($this->fixture));
        $this->event
            ->expects($this->any())
            ->method('addElapsedTime')
            ->will($this->returnSelf());

        $this->eventBuilder
            ->expects($this->any())
            ->method('withTopic')
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->any())
            ->method('withContent')
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->any())
            ->method('withPublisher')
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->any())
            ->method('build')
            ->will($this->returnValue($this->event));

        $this->composite->expects($this->any())
            ->method('count')
            ->will($this->returnValue(0));

        // Hydrator returns composite with count equal to array element count
        $callback = function ($values) {
            $mockBuilder = new \PHPUnit_Framework_MockObject_Generator;
            $composite   = $mockBuilder->getMock(
                'Deicer\Model\ModelCompositeInterface'
            );

            $composite
                ->expects(\PHPUnit_Framework_TestCase::any())
                ->method('count')
                ->will(\PHPUnit_Framework_TestCase::returnValue(count($values)));

            return $composite;
        };

        $this->hydrator->expects($this->any())
            ->method('exchangeArray')
            ->with($this->isType('array'))
            ->will($this->returnCallback($callback));

        $this->fixture = new TestableInvariableQueryWithValidFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );
    }

    public function setUpEventBuilder($topic, $content)
    {
        $this->event
            ->expects($this->atLeastOnce())
            ->method('getTopic')
            ->will($this->returnValue($topic));
        $this->event
            ->expects($this->atLeastOnce())
            ->method('getContent')
            ->will($this->returnValue($content));

        $this->eventBuilder
            ->expects($this->once())
            ->method('withTopic')
            ->with($this->equalTo($topic))
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->once())
            ->method('withContent')
            ->with($this->equalTo($content))
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->once())
            ->method('withPublisher')
            ->with($this->isInstanceOf('Deicer\Query\InvariableQueryInterface'))
            ->will($this->returnSelf());
        $this->eventBuilder
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($this->event));
    }

    public function setUpSubscriber($topic, $content)
    {
        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($event) use ($topic, $content) {
            if ($event->getTopic() != $topic) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Failed to notify of ' . $topic . ' with correct topic'
                );
            } elseif ($event->getContent() != $content) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Failed to notify of ' . $topic . ' with correct content'
                );
            }
        };

        $this->subscriber->expects($this->once())
            ->method('update')
            ->will($this->returnCallback($callback));
    }

    public function testGetLastResponseIsDefaultedToEmptyModelComposite()
    {
        $actual = $this->fixture->getLastResponse();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(0, $actual->count());
    }

    public function testSubscribeImplementsFluentInterface()
    {
        $actual = $this->fixture->subscribe($this->subscriber, 'foo');
        $this->assertSame($this->fixture, $actual);
    }

    public function testSubscribeWithNonStringTopicThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixture->subscribe($this->subscriber, null);
        $this->fixture->subscribe($this->subscriber, 1234);
        $this->fixture->subscribe($this->subscriber, array ());
        $this->fixture->subscribe($this->subscriber, new \stdClass());
    }

    public function testSubscribeWithEmptyStringTopicThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->fixture->subscribe($this->subscriber, '');
    }

    public function testSubscribeSubscribesSubscribersOnlyOnce()
    {
        $this->event->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $this->subscriber->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->event));

        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->publish($this->event);
    }

    public function testSubscribeSubscribesSubscriberToStatedTopic()
    {
        $fooEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');
        $barEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');
        $bazEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');

        $fooEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $barEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('bar'));
        $bazEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('baz'));

        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($event) {
            if ($event->getTopic() == 'bar') {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Publisher failed to filter event by topic'
                );
            }
        };

        $this->subscriber->expects($this->exactly(2))
            ->method('update')
            ->will($this->returnCallback($callback));

        // Subscribed to only foo and baz topics
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->subscribe($this->subscriber, 'baz');
        $this->fixture->publish($fooEvent);
        $this->fixture->publish($barEvent);
        $this->fixture->publish($bazEvent);
    }

    public function testUnsubscribeImplementsFluentInterface()
    {
        $this->fixture->subscribe($this->subscriber, 'foo');
        $actual = $this->fixture->unsubscribe($this->subscriber, 'foo');
        $this->assertSame($this->fixture, $actual);
    }

    public function testUnsubscribeWithNonStringTopicThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->unsubscribe($this->subscriber, null);
        $this->fixture->unsubscribe($this->subscriber, 1234);
        $this->fixture->unsubscribe($this->subscriber, array ());
        $this->fixture->unsubscribe($this->subscriber, new \stdClass());
    }

    public function testUnsubscribeWithEmptyStringTopicThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->unsubscribe($this->subscriber, '');
    }

    public function testUnsubscribeWithUnsubscribedSubscriberThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $this->fixture->unsubscribe($this->subscriber, 'foo');
    }

    public function testUnsubscribeWithUnsubscribedTopicThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->unsubscribe($this->subscriber, 'bar');
    }

    public function testUnsubscribeUnsubscribesSubscriberFromStatedTopic()
    {
        $fooEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');
        $barEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');
        $bazEvent = $this->getMock('Deicer\Stdlib\Pubsub\EventInterface');

        $fooEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $barEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('bar'));
        $bazEvent->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('baz'));

        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($event) {
            if ($event->getTopic() == 'foo') {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Publisher failed to filter event by topic'
                );
            }
        };

        $this->subscriber->expects($this->exactly(2))
            ->method('update')
            ->will($this->returnCallback($callback));

        // Unsubscribed to only foo topic
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->subscribe($this->subscriber, 'bar');
        $this->fixture->subscribe($this->subscriber, 'baz');
        $this->fixture->unsubscribe($this->subscriber, 'foo');
        $this->fixture->publish($fooEvent);
        $this->fixture->publish($barEvent);
        $this->fixture->publish($bazEvent);
    }

    public function testExecuteWithNonArrayReturningFetchDataThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');
        $fixture = new TestableInvariableQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );
        $fixture->execute();
    }

    public function testExecuteRethrowsDataProviderException()
    {
        $fixture = new TestableInvariableQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        try {
            $fixture->execute();
        } catch (DataFetchException $e) {
            $prev = $e->getPrevious();

            $this->assertInstanceOf('\Exception', $prev);
            $this->assertSame('foo', $prev->getMessage());
            $this->assertSame(123, $prev->getCode());

            $this->assertInstanceOf('\Exception', $prev->getPrevious());
            $this->assertSame('bar', $prev->getPrevious()->getMessage());
            $this->assertSame(456, $prev->getPrevious()->getCode());
            return;
        }

        $this->fail('Failed to rethrow data provider exception');
    }

    public function testExecuteRethrowsModelCompositeHydratorException()
    {
        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));
        $fixture = new TestableInvariableQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        try {
            $fixture->execute();
        } catch (ModelHydratorException $e) {
            $prev = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $prev);
            return;
        }

        $this->fail('Failed to rethrow model hydrator exception');
    }

    public function testExecuteReturnsHydratedResponse()
    {
        $actual = $this->fixture->execute();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
    }

    public function testExecuteUpdatesLastResponse()
    {
        $this->fixture->execute();
        $actual = $this->fixture->getLastResponse();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
    }

    public function testDecorateImplementsFluentInterface()
    {
        $mock = $this->fixture->decorate(
            $this->getMock('Deicer\Query\InvariableQueryInterface')
        );
        $this->assertSame($this->fixture->decorate($mock), $this->fixture);
    }

    public function testExecuteFallsBackToDecoratedExecutableOnModelHydratorFailure()
    {
        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));
        $fixture = new TestableInvariableQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->decorate($this->fixture);
        $actual = $fixture->execute();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $fixture->getLastResponse());
    }

    public function testExecuteFallsBackToDecoratedExecutableOnDataFetchFailure()
    {
        $fixture = new TestableInvariableQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->decorate($this->fixture);
        $actual = $fixture->execute();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $fixture->getLastResponse());
    }

    public function testExecuteFallsBackToDecoratedExecutableOnDataTypeFailure()
    {
        $fixture = new TestableInvariableQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->decorate($this->fixture);
        $actual = $fixture->execute();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $fixture->getLastResponse());
    }

    public function testExecuteRecordsTimeTakenToExecute()
    {
        $this->event
            ->expects($this->atLeastOnce())
            ->method('getTopic')
            ->will($this->returnValue('success'));
        $this->event
            ->expects($this->once())
            ->method('addElapsedTime')
            ->with($this->logicalAnd($this->isType('int'), $this->greaterThan(0)))
            ->will($this->returnSelf());

        $this->subscriber->expects($this->once())->method('update');

        $this->fixture->subscribe($this->subscriber, 'success');
        $this->fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfExecutionSuccess()
    {
        $topic   = 'success';
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
            ),
            array (
                'id'   => 2,
                'name' => 'bar',
            ),
        );

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        $fixture = new TestableInvariableQueryWithValidFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfModelHydratorFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\ModelHydratorException');

        $topic   = 'failure_model_hydrator';
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
                'role' => 'bar',
            ),
        );

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));

        $fixture = new TestableInvariableQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataFetchFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataFetchException');

        $topic   = 'failure_data_fetch';
        $content = array ();

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        $fixture = new TestableInvariableQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataTypeFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');

        $topic   = 'failure_data_type';
        $content = array ();

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        $fixture = new TestableInvariableQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToModelHydratorFailure()
    {
        $topic   = 'fallback_model_hydrator';
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
                'role' => 'bar',
            ),
        );

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));

        $fixture = new TestableInvariableQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\InvariableQueryInterface'));
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataFetchFailure()
    {
        $topic   = 'fallback_data_fetch';
        $content = array ();

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        $fixture = new TestableInvariableQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\InvariableQueryInterface'));
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataTypeFailure()
    {
        $topic   = 'fallback_data_type';
        $content = array ();

        $this->setUpEventBuilder($topic, $content);
        $this->setUpSubscriber($topic, $content);

        $fixture = new TestableInvariableQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->eventBuilder,
            $this->hydrator
        );

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\InvariableQueryInterface'));
        $fixture->execute();
    }
}

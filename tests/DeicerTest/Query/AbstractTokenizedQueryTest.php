<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use Deicer\Query\Exception\DataTypeException;
use Deicer\Query\Exception\DataFetchException;
use Deicer\Query\Exception\ModelHydratorException;
use DeicerTest\Query\TestableTokenizedQueryWithValidFetchData;
use DeicerTest\Query\TestableTokenizedQueryWithExceptionThrowingFetchData;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Tokenized Query unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class AbstractTokenizedQueryTest extends TestCase
{
    public $fixture;
    public $composite;
    public $hydrator;
    public $message;
    public $messageBuilder;
    public $subscriber;

    public function setUp()
    {
        $this->messageBuilder = $this->getMock(
            'Deicer\Query\Message\TokenizedQueryMessageBuilderInterface'
        );
        $this->composite = $this->getMock(
            'Deicer\Model\ModelCompositeInterface'
        );
        $this->hydrator = $this->getMock(
            'Deicer\Model\RecursiveModelCompositeHydratorInterface'
        );
        $this->message = $this->getMock(
            'Deicer\Query\Message\TokenizedQueryMessageInterface'
        );
        $this->subscriber = $this->getMock(
            'Deicer\Pubsub\SubscriberInterface'
        );

        $this->message
            ->expects($this->any())
            ->method('getPublisher')
            ->will($this->returnValue($this->fixture));
        $this->message
            ->expects($this->any())
            ->method('addElapsedTime')
            ->will($this->returnSelf());

        $this->messageBuilder
            ->expects($this->any())
            ->method('withTopic')
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->any())
            ->method('withContent')
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->any())
            ->method('withPublisher')
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->any())
            ->method('withToken')
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->any())
            ->method('build')
            ->will($this->returnValue($this->message));

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
                ->expects(TestCase::any())
                ->method('count')
                ->will(TestCase::returnValue(count($values)));

            return $composite;
        };

        $this->hydrator->expects($this->any())
            ->method('exchangeArray')
            ->with($this->isType('array'))
            ->will($this->returnCallback($callback));

        $this->fixture = new TestableTokenizedQueryWithValidFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );
    }

    public function setUpMessageBuilder($topic, $content, $token)
    {
        $this->message
            ->expects($this->atLeastOnce())
            ->method('getTopic')
            ->will($this->returnValue($topic));
        $this->message
            ->expects($this->atLeastOnce())
            ->method('getContent')
            ->will($this->returnValue($content));
        $this->message
            ->expects($this->atLeastOnce())
            ->method('getToken')
            ->will($this->returnValue($token));

        $this->messageBuilder
            ->expects($this->once())
            ->method('withTopic')
            ->with($this->equalTo($topic))
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->once())
            ->method('withContent')
            ->with($this->equalTo($content))
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->once())
            ->method('withPublisher')
            ->with($this->isInstanceOf('Deicer\Query\TokenizedQueryInterface'))
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->once())
            ->method('withToken')
            ->with($this->equalTo($token))
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($this->message));
    }

    public function setUpSubscriber($topic, $content, $token)
    {
        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($message) use ($topic, $content, $token) {
            if ($message->getTopic() != $topic) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Failed to notify of ' . $topic . ' with correct topic'
                );
            } elseif ($message->getContent() != $content) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Failed to notify of ' . $topic . ' with correct content'
                );
            } elseif ($message->getToken() != $token) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Failed to notify of ' . $token . ' with correct token'
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
        $this->message->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $this->subscriber->expects($this->once())
            ->method('update')
            ->with($this->equalTo($this->message));

        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->publish($this->message);
    }

    public function testSubscribeSubscribesSubscriberToStatedTopic()
    {
        $fooMessage = $this->getMock('Deicer\Pubsub\MessageInterface');
        $barMessage = $this->getMock('Deicer\Pubsub\MessageInterface');
        $bazMessage = $this->getMock('Deicer\Pubsub\MessageInterface');

        $fooMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $barMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('bar'));
        $bazMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('baz'));

        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($message) {
            if ($message->getTopic() == 'bar') {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Publisher failed to filter message by topic'
                );
            }
        };

        $this->subscriber->expects($this->exactly(2))
            ->method('update')
            ->will($this->returnCallback($callback));

        // Subscribed to only foo and baz topics
        $this->fixture->subscribe($this->subscriber, 'foo');
        $this->fixture->subscribe($this->subscriber, 'baz');
        $this->fixture->publish($fooMessage);
        $this->fixture->publish($barMessage);
        $this->fixture->publish($bazMessage);
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
        $fooMessage = $this->getMock('Deicer\Pubsub\MessageInterface');
        $barMessage = $this->getMock('Deicer\Pubsub\MessageInterface');
        $bazMessage = $this->getMock('Deicer\Pubsub\MessageInterface');

        $fooMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('foo'));
        $barMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('bar'));
        $bazMessage->expects($this->any())
            ->method('getTopic')
            ->will($this->returnValue('baz'));

        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($message) {
            if ($message->getTopic() == 'foo') {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    'Publisher failed to filter message by topic'
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
        $this->fixture->publish($fooMessage);
        $this->fixture->publish($barMessage);
        $this->fixture->publish($bazMessage);
    }

    public function testExecuteWithNonArrayReturningFetchDataThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');
        $fixture = new TestableTokenizedQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );
        $fixture->execute();
    }

    public function testExecuteRethrowsDataProviderException()
    {
        $fixture = new TestableTokenizedQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->messageBuilder,
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
        $fixture = new TestableTokenizedQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->messageBuilder,
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
            $this->getMock('Deicer\Query\TokenizedQueryInterface')
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
        $fixture = new TestableTokenizedQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->messageBuilder,
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
        $fixture = new TestableTokenizedQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->messageBuilder,
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
        $fixture = new TestableTokenizedQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->messageBuilder,
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
        $this->message
            ->expects($this->atLeastOnce())
            ->method('getTopic')
            ->will($this->returnValue('success'));
        $this->message
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
        $token   = 'foobar';
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

        $fixture = new TestableTokenizedQueryWithValidFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfModelHydratorFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\ModelHydratorException');

        $token   = 'foobar';
        $topic   = 'failure_model_hydrator';
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
                'role' => 'bar',
            ),
        );

        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));

        $fixture = new TestableTokenizedQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataFetchFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataFetchException');

        $token   = 'foobar';
        $topic   = 'failure_data_fetch';
        $content = array ();

        $fixture = new TestableTokenizedQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataTypeFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');

        $token   = 'foobar';
        $topic   = 'failure_data_type';
        $content = array ();

        $fixture = new TestableTokenizedQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToModelHydratorFailure()
    {
        $token   = 'foobar';
        $topic   = 'fallback_model_hydrator';
        $content = array (
            array (
                'id'   => 1,
                'name' => 'foo',
                'role' => 'bar',
            ),
        );

        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new \InvalidArgumentException($msg)));

        $fixture = new TestableTokenizedQueryWithModelIncompatibleFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\TokenizedQueryInterface'));
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataFetchFailure()
    {
        $token   = 'foobar';
        $topic   = 'fallback_data_fetch';
        $content = array ();

        $fixture = new TestableTokenizedQueryWithExceptionThrowingFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\TokenizedQueryInterface'));
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataTypeFailure()
    {
        $token   = 'foobar';
        $topic   = 'fallback_data_type';
        $content = array ();

        $fixture = new TestableTokenizedQueryWithNonArrayReturningFetchData(
            new \stdClass(),
            $this->messageBuilder,
            $this->hydrator
        );

        $this->setUpMessageBuilder($topic, $content, $token);
        $this->setUpSubscriber($topic, $content, $token);

        $fixture->subscribe($this->subscriber, $topic);
        $fixture->decorate($this->getMock('Deicer\Query\TokenizedQueryInterface'));
        $fixture->setToken($token);
        $fixture->execute();
    }

    public function testSetTokenImplementsFluentInterface()
    {
        $actual = $this->fixture->setToken('foo');
        $this->assertSame($actual, $this->fixture);
    }

    public function testSetTokenWithNonStringThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $actual = $this->fixture->setToken(array (1, 2, 3, 4));
    }

    public function testSetTokenInternalisesToken()
    {
        $this->fixture->setToken('foo');
        $this->assertSame('foo', $this->fixture->getToken());
    }
}

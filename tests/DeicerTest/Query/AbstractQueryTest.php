<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use Deicer\Query\Exception\DataFetchException;
use Deicer\Query\Exception\ModelHydratorException;
use Deicer\Pubsub\MessageInterface;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Abstract Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractQueryTest extends TestCase
{
    public $fixture;
    public $fixtureWithExceptionThrowingFetchData;
    public $fixtureWithNonArrayReturningFetchData;
    public $fixtureWithModelIncompatibleFetchData;
    public $mockFixture;
    public $composite;
    public $hydrator;
    public $message;
    public $unfilteredMessageBroker;
    public $topicFilteredMessageBroker;
    public $messageBuilder;
    public $subscriber;

    abstract public function setUpFixture();
    abstract public function setUpFixtureWithExceptionThrowingFetchData();
    abstract public function setUpFixtureWithNonArrayReturningFetchData();
    abstract public function setUpFixtureWithModelIncompatibleFetchData();
    abstract public function setUpMockFixture();

    public function setUp()
    {
        $this->unfilteredMessageBroker = $this->getMock(
            'Deicer\Pubsub\UnfilteredMessageBrokerInterface'
        );
        $this->topicFilteredMessageBroker = $this->getMock(
            'Deicer\Pubsub\TopicFilteredMessageBrokerInterface'
        );
        $this->messageBuilder = $this->getMock(
            'Deicer\Pubsub\MessageBuilderInterface'
        );
        $this->composite = $this->getMock(
            'Deicer\Model\ModelCompositeInterface'
        );
        $this->hydrator = $this->getMock(
            'Deicer\Model\RecursiveModelCompositeHydratorInterface'
        );
        $this->message = $this->getMock(
            'Deicer\Pubsub\MessageInterface'
        );
        $this->subscriber = $this->getMock(
            'Deicer\Pubsub\SubscriberInterface'
        );

        $this->message
            ->expects($this->any())
            ->method('getPublisher')
            ->will($this->returnValue($this->fixture));

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
            ->method('withAttributes')
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
            $mockBuilder = new MockGenerator;
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

        $this
            ->setUpFixture()
            ->setUpFixtureWithExceptionThrowingFetchData()
            ->setUpFixtureWithNonArrayReturningFetchData()
            ->setUpFixtureWithModelIncompatibleFetchData()
            ->setUpMockFixture();
    }

    public function setUpMessageBuilder($topic, $content, array $supplementaryAttribs = array ())
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
            ->method('getAttributes')
            ->will($this->returnValue(array ()));

        $builder  = $this->messageBuilder;
        $callback = function ($attribs) use ($builder, $supplementaryAttribs) {

            // Ensure elapsed time is recorded
            if (empty($attribs['elapsed_time'])) {
                throw new ExpectationFailedException(
                    'Failure to invoke message builder with elapsed_time attrib'
                );
            }

            if (empty($supplementaryAttribs)) {
                return $builder;
            }

            // Ensure supplementary attributes are present
            unset($attribs['elapsed_time']);
            if ($attribs != $supplementaryAttribs) {
                throw new ExpectationFailedException(
                    'Failure to invoke message builder with correct supplementary attribs'
                );
            }

            return $builder;
        };

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
            ->with($this->isInstanceOf('Deicer\Query\QueryInterface'))
            ->will($this->returnSelf());
        $this->messageBuilder
            ->expects($this->once())
            ->method('withAttributes')
            ->with($this->isType('array'))
            ->will($this->returnCallback($callback));
        $this->messageBuilder
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($this->message));

        return $this;
    }

    public function setUpMessageBrokers(MessageInterface $expectedMessage)
    {
        // Work-around for unsupported mutliple method invocation expectations
        $callback = function ($message) use ($expectedMessage) {
            if ($message != $expectedMessage) {
                throw new ExpectationFailedException(
                    'Failure to invoke message brokers with correct message'
                );
            }

            // Simulate message usage by broker
            $message->getTopic();
            $message->getContent();
            $message->getPublisher();
            $message->getAttributes();
        };

        $this->unfilteredMessageBroker->expects($this->once())
            ->method('publish')
            ->will($this->returnCallback($callback));
        $this->topicFilteredMessageBroker->expects($this->once())
            ->method('publish')
            ->will($this->returnCallback($callback));

        return $this;
    }

    public function testGetLastResponseIsDefaultedToEmptyModelComposite()
    {
        $actual = $this->fixture->getLastResponse();
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(0, $actual->count());
    }

    public function testGetUnfilteredMessageBrokerReturnsInstance()
    {
        $this->assertSame(
            $this->unfilteredMessageBroker,
            $this->fixture->getUnfilteredMessageBroker()
        );
    }

    public function testGetTopicFilteredMessageBrokerReturnsInstance()
    {
        $this->assertSame(
            $this->topicFilteredMessageBroker,
            $this->fixture->getTopicFilteredMessageBroker()
        );
    }

    public function testExecuteWithNonArrayReturningFetchDataThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');
        $this->fixtureWithNonArrayReturningFetchData->execute();
    }

    public function testExecuteRethrowsDataProviderException()
    {
        try {
            $this->fixtureWithExceptionThrowingFetchData->execute();
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
            ->will($this->throwException(new InvalidArgumentException($msg)));
        $this->setUpFixtureWithModelIncompatibleFetchData();

        try {
            $this->fixtureWithModelIncompatibleFetchData->execute();
        } catch (ModelHydratorException $e) {
            $prev = $e->getPrevious();
            $this->assertInstanceOf('InvalidArgumentException', $prev);
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
        $actual = $this->fixture->decorate($this->mockFixture);
        $this->assertSame($this->fixture->decorate($actual), $this->fixture);
    }

    public function testExecuteFallsBackToDecoratedExecutableOnModelHydratorFailure()
    {
        // exchangeArray called first at instantiation to set empty last reponse
        $msg = 'Unhandled hydrator exception';
        $this->hydrator->expects($this->at(1))
            ->method('exchangeArray')
            ->will($this->throwException(new InvalidArgumentException($msg)));

        $this->setUpFixtureWithModelIncompatibleFetchData();
        $this->fixtureWithModelIncompatibleFetchData->decorate($this->fixture);

        $actual = $this->fixtureWithModelIncompatibleFetchData->execute();
        $lastResponse = $this->fixtureWithModelIncompatibleFetchData->getLastResponse();

        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $lastResponse);
    }

    public function testExecuteFallsBackToDecoratedExecutableOnDataFetchFailure()
    {
        $this->fixtureWithExceptionThrowingFetchData->decorate($this->fixture);

        $actual = $this->fixtureWithExceptionThrowingFetchData->execute();
        $lastResponse = $this->fixtureWithExceptionThrowingFetchData->getLastResponse();

        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $lastResponse);
    }

    public function testExecuteFallsBackToDecoratedExecutableOnDataTypeFailure()
    {
        $this->fixtureWithNonArrayReturningFetchData->decorate($this->fixture);

        $actual = $this->fixtureWithNonArrayReturningFetchData->execute();
        $lastResponse = $this->fixtureWithNonArrayReturningFetchData->getLastResponse();

        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());
        $this->assertSame($actual, $lastResponse);
    }

    public function testExecuteNotifiesSubscribersOfExecutionSuccess()
    {
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

        $this->setUpMessageBuilder('success', $content);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixture();
        $this->fixture->execute();
    }

    public function testExecuteNotifiesSubscribersOfModelHydratorFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\ModelHydratorException');
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
            ->will($this->throwException(new InvalidArgumentException($msg)));

        $this->setUpMessageBuilder('failure.model_hydrator', $content);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithModelIncompatibleFetchData();
        $this->fixtureWithModelIncompatibleFetchData->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataFetchFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataFetchException');
        $this->setUpMessageBuilder('failure.data_fetch', null);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithExceptionThrowingFetchData();
        $this->fixtureWithExceptionThrowingFetchData->execute();
    }

    public function testExecuteNotifiesSubscribersOfDataTypeFailure()
    {
        $this->setExpectedException('Deicer\Query\Exception\DataTypeException');
        $this->setUpMessageBuilder('failure.data_type', null);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithNonArrayReturningFetchData();
        $this->fixtureWithNonArrayReturningFetchData->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToModelHydratorFailure()
    {
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
            ->will($this->throwException(new InvalidArgumentException($msg)));

        $this->setUpMessageBuilder('fallback.model_hydrator', $content);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithModelIncompatibleFetchData();
        $this->fixtureWithModelIncompatibleFetchData->decorate($this->mockFixture);
        $this->fixtureWithModelIncompatibleFetchData->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataFetchFailure()
    {
        $this->setUpMessageBuilder('fallback.data_fetch', null);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithExceptionThrowingFetchData();
        $this->fixtureWithExceptionThrowingFetchData->decorate($this->mockFixture);
        $this->fixtureWithExceptionThrowingFetchData->execute();
    }

    public function testExecuteNotifiesSubscribersOfFallbackDueToDataTypeFailure()
    {
        $this->setUpMessageBuilder('fallback.data_type', null);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixtureWithNonArrayReturningFetchData();
        $this->fixtureWithNonArrayReturningFetchData->decorate($this->mockFixture);
        $this->fixtureWithNonArrayReturningFetchData->execute();
    }
}

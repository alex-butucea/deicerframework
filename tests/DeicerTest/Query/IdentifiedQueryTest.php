<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use stdClass;
use DeicerTestAsset\Query\TestableIdentifiedQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableIdentifiedQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableIdentifiedQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableIdentifiedQueryWithModelIncompatibleFetchData;
use DeicerTest\Query\AbstractQueryTest;

/**
 * Deicer Identified Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class IdentifiedQueryTest extends AbstractQueryTest
{
    public function setUpFixture()
    {
        $this->fixture =
            new TestableIdentifiedQueryWithValidFetchData(
                new stdClass(),
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithExceptionThrowingFetchData()
    {
        $this->fixtureWithExceptionThrowingFetchData =
            new TestableIdentifiedQueryWithExceptionThrowingFetchData(
                new stdClass(),
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithNonArrayReturningFetchData()
    {
        $this->fixtureWithNonArrayReturningFetchData =
            new TestableIdentifiedQueryWithNonArrayReturningFetchData(
                new stdClass(),
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithModelIncompatibleFetchData()
    {
        $this->fixtureWithModelIncompatibleFetchData =
            new TestableIdentifiedQueryWithModelIncompatibleFetchData(
                new stdClass(),
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpMockFixture()
    {
        $this->mockFixture = $this->getMock(
            'Deicer\Query\IdentifiedQueryInterface'
        );

        return $this;
    }

    public function testPublishedMessagesContainIdAttribute()
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

        $this->setUpMessageBuilder('success', $content, array ('id' => 1234));
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixture();
        $this->fixture->setId(1234);
        $this->fixture->execute();
    }

    public function testSetIdImplementsFluentInterface()
    {
        $actual = $this->fixture->setId(1234);
        $this->assertSame($actual, $this->fixture);
    }

    public function testSetIdWithNonIntThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $actual = $this->fixture->setId(array (1, 2, 3, 4));
    }

    public function testSetIdInternalisesId()
    {
        $this->fixture->setId(1234);
        $this->assertSame(1234, $this->fixture->getId());
    }
}

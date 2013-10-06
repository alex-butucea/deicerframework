<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use DeicerTestAsset\Query\TestableTokenizedQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableTokenizedQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableTokenizedQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableTokenizedQueryWithModelIncompatibleFetchData;
use DeicerTest\Query\AbstractQueryTest;

/**
 * Deicer Tokenized Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedQueryTest extends AbstractQueryTest
{
    public function setUpFixture()
    {
        $this->fixture =
            new TestableTokenizedQueryWithValidFetchData(
                new \stdClass(),
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
            new TestableTokenizedQueryWithExceptionThrowingFetchData(
                new \stdClass(),
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
            new TestableTokenizedQueryWithNonArrayReturningFetchData(
                new \stdClass(),
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
            new TestableTokenizedQueryWithModelIncompatibleFetchData(
                new \stdClass(),
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
            'Deicer\Query\TokenizedQueryInterface'
        );

        return $this;
    }

    public function testPublishedMessagesContainTokenAttribute()
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

        $this->setUpMessageBuilder('success', $content, array ('token' => 'foo'));
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixture();
        $this->fixture->setToken('foo');
        $this->fixture->execute();
    }

    public function testSetTokenImplementsFluentInterface()
    {
        $actual = $this->fixture->setToken('foo');
        $this->assertSame($actual, $this->fixture);
    }

    public function testSetTokenWithNonStringThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $actual = $this->fixture->setToken(array (1, 2, 3, 4));
    }

    public function testSetTokenInternalisesToken()
    {
        $this->fixture->setToken('foo');
        $this->assertSame('foo', $this->fixture->getToken());
    }
}

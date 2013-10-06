<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use DeicerTestAsset\Query\TestableParameterizedQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithModelIncompatibleFetchData;
use DeicerTest\Query\AbstractQueryTest;

/**
 * Deicer Parameterized Query tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryTest extends AbstractQueryTest
{
    public function setUpFixture()
    {
        $this->fixture =
            new TestableParameterizedQueryWithValidFetchData(
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
            new TestableParameterizedQueryWithExceptionThrowingFetchData(
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
            new TestableParameterizedQueryWithNonArrayReturningFetchData(
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
            new TestableParameterizedQueryWithModelIncompatibleFetchData(
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
            'Deicer\Query\ParameterizedQueryInterface'
        );

        return $this;
    }

    public function testPublishedMessagesContainParamsAsAttributes()
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
        $params  = array (
            'genre'  => 'thriller',
            'year'   => 2013,
            'author' => 'Alex Butucea',
        );

        $this->setUpMessageBuilder('success', $content, $params);
        $this->setUpMessageBrokers($this->message);
        $this->setUpFixture();
        $this->fixture->setParams($params);
        $this->fixture->execute();
    }

    public function testSetParamImplementsFluentInterface()
    {
        $actual = $this->fixture->setParam('author', 'foobar');
        $this->assertSame($this->fixture, $actual);
    }

    public function testSetParamsImplementsFluentInterface()
    {
        $actual = $this->fixture->setParams(array ());
        $this->assertSame($this->fixture, $actual);
    }

    public function testSetParamNameTypeStrength()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->setParam(array (), 'bar');
    }

    public function testSetParamWithNonExistentParamThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\NonExistentParamException');
        $this->fixture->setParam('foo', 'bar');
    }

    public function testSetParamsWithNonExistentParamThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\NonExistentParamException');
        $this->fixture->setParams(
            array (
                'foo' => 'bar',
                'baz' => 'qux',
            )
        );
    }

    public function testTrySetParamsImplementsFluentInterface()
    {
        $actual = $this->fixture->trySetParams(
            array (
                'foo' => 'bar',
                'baz' => 'qux',
            )
        );
        $this->assertSame($actual, $this->fixture);
    }

    public function testGetParamNameTypeStrength()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->getParam(array (), 'bar');
    }

    public function testGetParamWithNonExistentParamThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\NonExistentParamException');
        $this->fixture->getParam('foo');
    }

    public function testGetParamReturnsInternalisedParam()
    {
        $this->fixture->setParam('genre', 'action');
        $this->assertSame('action', $this->fixture->getParam('genre'));
    }

    public function testGetParamsReturnsInternalisedParams()
    {
        $params  = array (
            'genre'  => 'thriller',
            'year'   => 2013,
            'author' => 'Alex Butucea',
        );
        $this->fixture->setParams($params);
        $this->assertSame($params, $this->fixture->getParams());
    }
}

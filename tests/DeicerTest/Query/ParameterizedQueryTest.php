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
use DeicerTestAsset\Query\TestableParameterizedQueryWithValidFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithExceptionThrowingFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithNonArrayReturningFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithEmptyArrayReturningFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithModelIncompatibleFetchData;
use DeicerTestAsset\Query\TestableParameterizedQueryWithIncompatibleParams;
use DeicerTestAsset\Query\TestableParameterizedQueryWithDataProviderDependency;
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
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithEmptyArrayReturningFetchData()
    {
        $this->fixtureWithEmptyArrayReturningFetchData =
            new TestableParameterizedQueryWithEmptyArrayReturningFetchData(
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
                $this->messageBuilder,
                $this->unfilteredMessageBroker,
                $this->topicFilteredMessageBroker,
                $this->hydrator
            );

        return $this;
    }

    public function setUpFixtureWithDataProviderDependency()
    {
        $this->fixtureWithDataProviderDependency =
            new TestableParameterizedQueryWithDataProviderDependency(
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

        $params = array (
            'genre'  => '',
            'year'   => 0,
            'author' => '',
        );

        $this->mockFixture
            ->expects($this->any())
            ->method('getParams')
            ->will($this->returnValue($params));

        return $this;
    }

    public function setUpFixtureParams()
    {
        $this->fixture->setParams(
            array (
                'genre'  => 'thriller',
                'year'   => 2013,
                'author' => 'Alex Butucea',
            )
        );
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
        $this->setUpFixtureParams();
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
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
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

    /**
     * @dataProvider providerNonScalar
     */
    public function testSetParamValueTypeStrength($value)
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $this->fixture->setParam('author', $value);
    }

    /**
     * @dataProvider providerNonScalars
     */
    public function testSetParamsValueTypeStrength($params)
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $this->fixture->setParams($params);
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

    public function testTrySetParamsSkipsNonStringKeys()
    {
        $this->setUpFixtureParams();
        $this->fixture->trySetParams(
            array (
                0       => 'foo',
                'genre' => 'comedy',
            )
        );

        $expected = array (
            'genre'  => 'comedy',
            'year'   => 2013,
            'author' => 'Alex Butucea',
        );

        $this->assertSame($expected, $this->fixture->getParams());
    }

    public function testTrySetParamsSkipsNonExistentParams()
    {
        $this->setupfixtureparams();
        $this->fixture->trySetParams(
            array (
                'foo'   => 'bar',
                'genre' => 'comedy',
            )
        );

        $expected = array (
            'genre'  => 'comedy',
            'year'   => 2013,
            'author' => 'Alex Butucea',
        );

        $this->assertSame($expected, $this->fixture->getParams());
    }

    public function testTrySetParamsSkipsNonScalarOrNullValues()
    {
        $this->setupfixtureparams();
        $this->fixture->trySetParams(
            array (
                'genre'  => new stdClass(),
                'year'   => 2010,
                'author' => array (),
            )
        );

        $expected = array (
            'genre'  => 'thriller',
            'year'   => 2010,
            'author' => 'Alex Butucea',
        );

        $this->assertSame($expected, $this->fixture->getParams());
    }

    public function testGetParamNameTypeStrength()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
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

    public function providerNonScalar()
    {
        return array (
            array (array ()),
            array (new stdClass()),
        );
    }

    public function providerNonScalars()
    {
        return array (
            array (
                array (
                    'genre'  => array (),
                    'year'   => array (),
                    'author' => array (),
                ),
            ),
            array (
                array (
                    'genre'  => new stdClass(),
                    'year'   => new stdClass(),
                    'author' => new stdClass(),
                ),
            ),
        );
    }

    public function testDecorateWithParamIncompatibleQueryThrowsException()
    {
        $this->setExpectedException('Deicer\Query\Exception\IncompatibleParamsException');
        $incompatible = new TestableParameterizedQueryWithIncompatibleParams(
            $this->messageBuilder,
            $this->unfilteredMessageBroker,
            $this->topicFilteredMessageBroker,
            $this->hydrator
        );
        $this->fixture->decorate($incompatible);
    }
}

<?php

namespace DeicerTest\Query\Event;

use Deicer\Query\Event\ParameterizedQueryEvent;

/**
 * Deicer Parameterized Query Event unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public $params = array (
        'foo' => 'bar',
        'baz' => 'qux',
    );

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\ParameterizedQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new ParameterizedQueryEvent('foo', null, $this->mockQuery, array ());
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new ParameterizedQueryEvent('', 'bar', $this->mockQuery, array ());
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new ParameterizedQueryEvent('', 'bar', $this->mockQuery, array ());
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesParams()
    {
        $fixture = new ParameterizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame($this->params, $fixture->getParams());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new ParameterizedQueryEvent(null, null, $this->mockQuery, array ());
        new ParameterizedQueryEvent(1234, null, $this->mockQuery, array ());
        new ParameterizedQueryEvent(array (), null, $this->mockQuery, array ());
        new ParameterizedQueryEvent(new \stdClass(), null, $this->mockQuery, array ());
    }

    public function testGetParamWithValidNameReturnsCorrectParam()
    {
        $fixture = new ParameterizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame('bar', $fixture->getParam('foo'));
        $this->assertSame('qux', $fixture->getParam('baz'));
    }

    public function testGetParamWithInvalidNameThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $fixture = new ParameterizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $fixture->getParam('foobar');
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new ParameterizedQueryEvent('', '', $this->mockQuery, array ());
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new ParameterizedQueryEvent('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(0);
        $this->assertSame(0, $fixture->getElapsedTime());
        $fixture->addElapsedTime(123);
        $this->assertSame(123, $fixture->getElapsedTime());
        $fixture->addElapsedTime(1);
        $this->assertSame(124, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonIntException');
        $fixture = new ParameterizedQueryEvent('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new ParameterizedQueryEvent('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(-1);
    }
}

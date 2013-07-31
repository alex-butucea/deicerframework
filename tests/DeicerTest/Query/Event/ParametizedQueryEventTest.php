<?php

namespace DeicerTest\Query\Event;

use Deicer\Query\Event\ParametizedQueryEvent;

/**
 * Deicer Parametized Query Event unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedQueryEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public $params = array (
        'foo' => 'bar',
        'baz' => 'qux',
    );

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\ParametizedQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new ParametizedQueryEvent('foo', null, $this->mockQuery, array ());
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new ParametizedQueryEvent('', 'bar', $this->mockQuery, array ());
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new ParametizedQueryEvent('', 'bar', $this->mockQuery, array ());
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesParams()
    {
        $fixture = new ParametizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame($this->params, $fixture->getParams());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new ParametizedQueryEvent(null, null, $this->mockQuery, array ());
        new ParametizedQueryEvent(1234, null, $this->mockQuery, array ());
        new ParametizedQueryEvent(array (), null, $this->mockQuery, array ());
        new ParametizedQueryEvent(new \stdClass(), null, $this->mockQuery, array ());
    }

    public function testGetParamWithValidNameReturnsCorrectParam()
    {
        $fixture = new ParametizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame('bar', $fixture->getParam('foo'));
        $this->assertSame('qux', $fixture->getParam('baz'));
    }

    public function testGetParamWithInvalidNameThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $fixture = new ParametizedQueryEvent('foo', null, $this->mockQuery, $this->params);
        $fixture->getParam('foobar');
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new ParametizedQueryEvent('', '', $this->mockQuery, array ());
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new ParametizedQueryEvent('', '', $this->mockQuery, array ());
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
        $fixture = new ParametizedQueryEvent('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new ParametizedQueryEvent('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(-1);
    }
}

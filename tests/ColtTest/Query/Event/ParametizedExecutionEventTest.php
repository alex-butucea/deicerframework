<?php

namespace ColtTest\Query\Event;

use Colt\Query\Event\ParametizedExecutionEvent;

/**
 * Colt Parametized Query Execution Event unit test suite
 * 
 * @category   ColtTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedExecutionEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public $params = array (
        'foo' => 'bar',
        'baz' => 'qux',
    );

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Colt\Query\ParametizedQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new ParametizedExecutionEvent('foo', null, $this->mockQuery);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new ParametizedExecutionEvent('', 'bar', $this->mockQuery);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new ParametizedExecutionEvent('', 'bar', $this->mockQuery);
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesParams()
    {
        $fixture = new ParametizedExecutionEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame($this->params, $fixture->getParams());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
        new ParametizedExecutionEvent(null, null, $this->mockQuery);
        new ParametizedExecutionEvent(1234, null, $this->mockQuery);
        new ParametizedExecutionEvent(array (), null, $this->mockQuery);
        new ParametizedExecutionEvent(new \stdClass(), null, $this->mockQuery);
    }

    public function testGetParamWithValidNameReturnsCorrectParam()
    {
        $fixture = new ParametizedExecutionEvent('foo', null, $this->mockQuery, $this->params);
        $this->assertSame('bar', $fixture->getParam('foo'));
        $this->assertSame('qux', $fixture->getParam('baz'));
    }

    public function testGetParamWithInvalidNameThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $fixture = new ParametizedExecutionEvent('foo', null, $this->mockQuery, $this->params);
        $fixture->getParam('foobar');
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new ParametizedExecutionEvent('', '', $this->mockQuery);
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new ParametizedExecutionEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(0);
        $this->assertSame(0, $fixture->getElapsedTime());
        $fixture->addElapsedTime(123);
        $this->assertSame(123, $fixture->getElapsedTime());
        $fixture->addElapsedTime(1);
        $this->assertSame(124, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonIntException');
        $fixture = new ParametizedExecutionEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new ParametizedExecutionEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(-1);
    }
}

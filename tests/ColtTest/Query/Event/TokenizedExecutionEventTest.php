<?php

namespace ColtTest\Query\Event;

use Colt\Query\Event\TokenizedExecutionEvent;

/**
 * Colt Tokenized Query Execution Event unit test suite
 * 
 * @category   ColtTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedExecutionEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Colt\Query\TokenizedQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new TokenizedExecutionEvent('foo', null, $this->mockQuery);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new TokenizedExecutionEvent('', 'bar', $this->mockQuery);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new TokenizedExecutionEvent('', 'bar', $this->mockQuery);
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesToken()
    {
        $fixture = new TokenizedExecutionEvent('foo', null, $this->mockQuery, 'bar');
        $this->assertSame('bar', $fixture->getToken());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
        new TokenizedExecutionEvent(null, null, $this->mockQuery);
        new TokenizedExecutionEvent(1234, null, $this->mockQuery);
        new TokenizedExecutionEvent(array (), null, $this->mockQuery);
        new TokenizedExecutionEvent(new \stdClass(), null, $this->mockQuery);
    }

    public function testConstructorTokenTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
        new TokenizedExecutionEvent('', null, $this->mockQuery, null);
        new TokenizedExecutionEvent('', null, $this->mockQuery, 1234);
        new TokenizedExecutionEvent('', null, $this->mockQuery, array ());
        new TokenizedExecutionEvent('', null, $this->mockQuery, new \stdClass());
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new TokenizedExecutionEvent('', '', $this->mockQuery);
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new TokenizedExecutionEvent('', '', $this->mockQuery);
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
        $fixture = new TokenizedExecutionEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new TokenizedExecutionEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(-1);
    }
}

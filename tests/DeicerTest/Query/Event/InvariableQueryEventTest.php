<?php

namespace DeicerTest\Query\Event;

use Deicer\Query\Event\QueryEventInterface;
use Deicer\Query\Event\InvariableQueryEvent;

/**
 * Deicer Invariable Query Event unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\InvariableQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new InvariableQueryEvent('foo', null, $this->mockQuery);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new InvariableQueryEvent('', 'bar', $this->mockQuery);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new InvariableQueryEvent('', 'bar', $this->mockQuery);
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new InvariableQueryEvent(null, null, $this->mockQuery);
        new InvariableQueryEvent(1234, null, $this->mockQuery);
        new InvariableQueryEvent(array (), null, $this->mockQuery);
        new InvariableQueryEvent(new \stdClass(), null, $this->mockQuery);
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new InvariableQueryEvent('', '', $this->mockQuery);
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new InvariableQueryEvent('', '', $this->mockQuery);
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
        $fixture = new InvariableQueryEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new InvariableQueryEvent('', '', $this->mockQuery);
        $fixture->addElapsedTime(-1);
    }

    public function testToStringSerializesEventStateCorrectly()
    {
        $publisher = $this->getMock('Deicer\Query\InvariableQueryInterface');
        $content   = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '/^Invariable Query Execution: (.)+InvariableQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_model_hydrator" \| ';
        $regex .= 'Elapsed Time: 1234ms \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new InvariableQueryEvent('failure_model_hydrator', $content, $publisher);
        $fixture->addElapsedTime(1234);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

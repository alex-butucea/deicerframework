<?php

namespace DeicerTest\Query\Event;

use Deicer\Query\Event\TokenizedQueryEvent;

/**
 * Deicer Tokenized Query Event unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedQueryEventTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\TokenizedQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new TokenizedQueryEvent('foo', null, $this->mockQuery, '');
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new TokenizedQueryEvent('', 'bar', $this->mockQuery, '');
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new TokenizedQueryEvent('', 'bar', $this->mockQuery, '');
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesToken()
    {
        $fixture = new TokenizedQueryEvent('foo', null, $this->mockQuery, 'bar');
        $this->assertSame('bar', $fixture->getToken());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new TokenizedQueryEvent(null, null, $this->mockQuery, '');
        new TokenizedQueryEvent(1234, null, $this->mockQuery, '');
        new TokenizedQueryEvent(array (), null, $this->mockQuery, '');
        new TokenizedQueryEvent(new \stdClass(), null, $this->mockQuery, '');
    }

    public function testConstructorTokenTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new TokenizedQueryEvent('', null, $this->mockQuery, null);
        new TokenizedQueryEvent('', null, $this->mockQuery, 1234);
        new TokenizedQueryEvent('', null, $this->mockQuery, array ());
        new TokenizedQueryEvent('', null, $this->mockQuery, new \stdClass());
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new TokenizedQueryEvent('', '', $this->mockQuery, '');
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new TokenizedQueryEvent('', '', $this->mockQuery, '');
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
        $fixture = new TokenizedQueryEvent('', '', $this->mockQuery, '');
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new TokenizedQueryEvent('', '', $this->mockQuery, '');
        $fixture->addElapsedTime(-1);
    }

    public function testToStringSerializesEventStateCorrectly()
    {
        $publisher = $this->getMock('Deicer\Query\TokenizedQueryInterface');
        $content   = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '/^Tokenized Query Execution: (.)+TokenizedQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_data_type" \| ';
        $regex .= 'Elapsed Time: 567ms \| ';
        $regex .= 'Token: "foobar" \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new TokenizedQueryEvent(
            'failure_data_type',
            $content,
            $publisher,
            'foobar'    
        );
        $fixture->addElapsedTime(567);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

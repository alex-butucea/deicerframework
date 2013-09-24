<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * Deicer Abstract Query Message tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
abstract class AbstractQueryMessageTest extends TestCase
{
    public $mockQuery;

    abstract public function fixtureFactory($topic, $content, $publisher);

    public function testConstructorInternalisesTopic()
    {
        $fixture = $this->fixtureFactory('foo', null, $this->mockQuery);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = $this->fixtureFactory('', 'bar', $this->mockQuery);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = $this->fixtureFactory('', 'bar', $this->mockQuery);
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixtureFactory(null, null, $this->mockQuery);
        $this->fixtureFactory(1234, null, $this->mockQuery);
        $this->fixtureFactory(array (), null, $this->mockQuery);
        $this->fixtureFactory(new \stdClass(), null, $this->mockQuery);
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = $this->fixtureFactory('', '', $this->mockQuery);
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = $this->fixtureFactory('', '', $this->mockQuery);
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
        $fixture = $this->fixtureFactory('', '', $this->mockQuery);
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new \stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = $this->fixtureFactory('', '', $this->mockQuery);
        $fixture->addElapsedTime(-1);
    }
}

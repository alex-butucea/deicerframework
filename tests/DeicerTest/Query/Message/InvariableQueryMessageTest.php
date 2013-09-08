<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\QueryMessageInterface;
use Deicer\Query\Message\InvariableQueryMessage;

/**
 * Deicer Invariable Query Message unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryMessageTest extends \PHPUnit_Framework_TestCase
{
    public $mockQuery;

    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\InvariableQueryInterface');
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = new InvariableQueryMessage('foo', null, $this->mockQuery);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new InvariableQueryMessage('', 'bar', $this->mockQuery);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new InvariableQueryMessage('', 'bar', $this->mockQuery);
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new InvariableQueryMessage(null, null, $this->mockQuery);
        new InvariableQueryMessage(1234, null, $this->mockQuery);
        new InvariableQueryMessage(array (), null, $this->mockQuery);
        new InvariableQueryMessage(new \stdClass(), null, $this->mockQuery);
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new InvariableQueryMessage('', '', $this->mockQuery);
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new InvariableQueryMessage('', '', $this->mockQuery);
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
        $fixture = new InvariableQueryMessage('', '', $this->mockQuery);
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new InvariableQueryMessage('', '', $this->mockQuery);
        $fixture->addElapsedTime(-1);
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $publisher = $this->getMock('Deicer\Query\InvariableQueryInterface');
        $content   = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '/^Invariable Query Execution: (.)+InvariableQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_model_hydrator" \| ';
        $regex .= 'Elapsed Time: 1234ms \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new InvariableQueryMessage('failure_model_hydrator', $content, $publisher);
        $fixture->addElapsedTime(1234);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

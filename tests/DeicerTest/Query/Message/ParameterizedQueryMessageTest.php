<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\ParameterizedQueryMessage;

/**
 * Deicer Parameterized Query Message unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryMessageTest extends \PHPUnit_Framework_TestCase
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
        $fixture = new ParameterizedQueryMessage('foo', null, $this->mockQuery, array ());
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = new ParameterizedQueryMessage('', 'bar', $this->mockQuery, array ());
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = new ParameterizedQueryMessage('', 'bar', $this->mockQuery, array ());
        $this->assertSame($this->mockQuery, $fixture->getPublisher());
    }

    public function testConstructorInternalisesParams()
    {
        $fixture = new ParameterizedQueryMessage('foo', null, $this->mockQuery, $this->params);
        $this->assertSame($this->params, $fixture->getParams());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new ParameterizedQueryMessage(null, null, $this->mockQuery, array ());
        new ParameterizedQueryMessage(1234, null, $this->mockQuery, array ());
        new ParameterizedQueryMessage(array (), null, $this->mockQuery, array ());
        new ParameterizedQueryMessage(new \stdClass(), null, $this->mockQuery, array ());
    }

    public function testGetParamWithValidNameReturnsCorrectParam()
    {
        $fixture = new ParameterizedQueryMessage('foo', null, $this->mockQuery, $this->params);
        $this->assertSame('bar', $fixture->getParam('foo'));
        $this->assertSame('qux', $fixture->getParam('baz'));
    }

    public function testGetParamWithInvalidNameThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $fixture = new ParameterizedQueryMessage('foo', null, $this->mockQuery, $this->params);
        $fixture->getParam('foobar');
    }

    public function testGetElapsedTimeDefaultsToZero()
    {
        $fixture = new ParameterizedQueryMessage('', '', $this->mockQuery, array ());
        $this->assertSame(0, $fixture->getElapsedTime());
    }

    public function testAddElapsedTimeIncrementsCorrectly()
    {
        $fixture = new ParameterizedQueryMessage('', '', $this->mockQuery, array ());
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
        $fixture = new ParameterizedQueryMessage('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(null);
        $fixture->addElapsedTime('foo');
        $fixture->addElapsedTime(array ());
        $fixture->addElapsedTime(new stdClass());
    }

    public function testAddElapsedTimeRejectsNegativeIntervals()
    {
        $this->setExpectedException('\RangeException');
        $fixture = new ParameterizedQueryMessage('', '', $this->mockQuery, array ());
        $fixture->addElapsedTime(-1);
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $publisher = $this->getMock('Deicer\Query\ParameterizedQueryInterface');
        $content   = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));
        $params    = array ('foobar' => 'foobaz', 'quux' => 1234);

        $regex  = '/^Parameterized Query Execution: (.)+ParameterizedQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_data_fetch" \| ';
        $regex .= 'Elapsed Time: 890ms \| ';
        $regex .= 'Params: ' . preg_quote(json_encode($params)) . ' \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new ParameterizedQueryMessage(
            'failure_data_fetch',
            $content,
            $publisher,
            $params
        );
        $fixture->addElapsedTime(890);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

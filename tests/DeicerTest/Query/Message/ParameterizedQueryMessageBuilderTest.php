<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\ParameterizedQueryMessageBuilder;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Parameterized Query Message Builder unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryMessageBuilderTest extends TestCase
{
    public $fixture;
    public $mockPublisher;

    public function setUp()
    {
        $this->fixture = new ParameterizedQueryMessageBuilder();
        $this->mockPublisher = $this->getMock('Deicer\Query\ParameterizedQueryInterface');
    }

    public function testWithTopicImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withTopic('foo'));
    }

    public function testWithContentImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withContent('bar'));
    }

    public function testWithPublisherImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withPublisher($this->mockPublisher));
    }

    public function testWithParamsImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withParams(array ('foo' => 'bar')));
    }

    public function testWithTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixture->withTopic(null);
        $this->fixture->withTopic(1234);
        $this->fixture->withTopic(array ());
        $this->fixture->withTopic(new \stdClass());
    }

    public function testBuildThrowsExceptionIfTopicIsEmpty()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withContent('foo')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
    }

    public function testBuildThrowsExceptionIfContentIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
    }

    public function testBuildThrowsExceptionIfPublisherIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withParams(array ('foo' => 'bar'))
            ->build();
    }

    public function testBuildThrowsExceptionIfParamsIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withPublisher($this->mockPublisher)
            ->withContent('bar')
            ->build();
    }

    public function testBuildReturnsInstanceOfParameterizedQueryMessage()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertInstanceOf('Deicer\Query\Message\ParameterizedQueryMessage', $built);
    }

    public function testBuildUsesSetTopic()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertSame('foo', $built->getTopic());
    }

    public function testBuildUsesSetContent()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertSame('bar', $built->getContent());
    }

    public function testBuildUsesSetPublisher()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertSame($this->mockPublisher, $built->getPublisher());
    }

    public function testBuildUsesSetParams()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertSame(array ('foo' => 'bar'), $built->getParams());
    }
}

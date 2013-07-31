<?php

namespace ColtTest\Query\Event;

use Colt\Query\Event\ParametizedQueryEventBuilder;

/**
 * Colt Parametized Query Event Builder unit test suite
 * 
 * @category   ColtTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParametizedQueryEventBuilderTest extends \PHPUnit_Framework_TestCase
{
    public $fixture;
    public $mockPublisher;

    public function setUp()
    {
        $this->fixture = new ParametizedQueryEventBuilder();
        $this->mockPublisher = $this->getMock('Colt\Query\ParametizedQueryInterface');
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
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
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

    public function testBuildReturnsInstanceOfParametizedQueryEvent()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withParams(array ('foo' => 'bar'))
            ->build();
        $this->assertInstanceOf('Colt\Query\Event\ParametizedQueryEvent', $built);
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

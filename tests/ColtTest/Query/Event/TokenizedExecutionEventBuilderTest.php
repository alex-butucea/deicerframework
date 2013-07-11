<?php

namespace ColtTest\Query\Event;

use Colt\Query\Event\TokenizedExecutionEventBuilder;

/**
 * Colt Tokenized Query Execution Event Builder unit test suite
 * 
 * @category   ColtTest
 * @package    Query
 * @subpackage Event
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedExecutionEventBuilderTest extends \PHPUnit_Framework_TestCase
{
    public $fixture;
    public $mockPublisher;

    public function setUp()
    {
        $this->fixture = new TokenizedExecutionEventBuilder();
        $this->mockPublisher = $this->getMock('Colt\Query\TokenizedQueryInterface');
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

    public function testWithTokenImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withToken('baz'));
    }

    public function testWithTopicTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
        $this->fixture->withTopic(null);
        $this->fixture->withTopic(1234);
        $this->fixture->withTopic(array ());
        $this->fixture->withTopic(new \stdClass());
    }

    public function testWithTokenTypeStrength()
    {
        $this->setExpectedException('Colt\Exception\Type\NonStringException');
        $this->fixture->withToken(null);
        $this->fixture->withToken(1234);
        $this->fixture->withToken(array ());
        $this->fixture->withToken(new \stdClass());
    }

    public function testBuildThrowsExceptionIfTopicIsEmpty()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withContent('foo')
            ->withPublisher($this->mockPublisher)
            ->withToken('bar')
            ->build();
    }

    public function testBuildThrowsExceptionIfContentIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withPublisher($this->mockPublisher)
            ->withToken('bar')
            ->build();
    }

    public function testBuildThrowsExceptionIfPublisherIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withToken('baz')
            ->build();
    }

    public function testBuildThrowsExceptionIfTokenIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withPublisher($this->mockPublisher)
            ->withContent('bar')
            ->build();
    }

    public function testBuildReturnsInstanceOfTokenizedExecutionEvent()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withToken('baz')
            ->build();
        $this->assertInstanceOf('Colt\Query\Event\TokenizedExecutionEvent', $built);
    }

    public function testBuildUsesSetTopic()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withToken('baz')
            ->build();
        $this->assertSame('foo', $built->getTopic());
    }

    public function testBuildUsesSetContent()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withToken('baz')
            ->build();
        $this->assertSame('bar', $built->getContent());
    }

    public function testBuildUsesSetPublisher()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withToken('baz')
            ->build();
        $this->assertSame($this->mockPublisher, $built->getPublisher());
    }

    public function testBuildUsesSetToken()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withToken('baz')
            ->build();
        $this->assertSame('baz', $built->getToken());
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

use stdClass;
use Deicer\Pubsub\MessageBuilder;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Pubsub Message Builder tests
 * 
 * @category   DeicerTest
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class MessageBuilderTest extends TestCase
{
    public $fixture;
    public $mockPublisher;
    public $attributes = array (
        'foo' => 'bar',
        'baz' => 'qux',
    );

    public function setUp()
    {
        $this->fixture = new MessageBuilder();
        $this->mockPublisher = $this->getMock('Deicer\Pubsub\PublisherInterface');
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

    public function testWithAttributesImplementsFluentInterface()
    {
        $this->assertSame($this->fixture, $this->fixture->withAttributes($this->attributes));
    }

    public function testWithAttributesWithArrayContainingNonSringKeyThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->attributes[1] = 'quux';
        $this->fixture->withAttributes($this->attributes);
    }

    public function testWithAttributesWithArrayContainingEmptyKeyThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->attributes[''] = 'quux';
        $this->fixture->withAttributes($this->attributes);
    }

    public function testWithAttributesWithArrayContainingNonScalarValueThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->attributes['quux'] = new stdClass();
        $this->fixture->withAttributes($this->attributes);
    }

    public function testWithTopicThrowsExceptionIfTopicIsEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->withTopic('');
    }

    public function testWithTopicTypeStrength()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture->withTopic(array ());
    }

    public function testBuildThrowsExceptionIfTopicIsEmpty()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withContent('foo')
            ->withPublisher($this->mockPublisher)
            ->build();
    }

    public function testBuildThrowsExceptionIfPublisherIsUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->build();
    }

    public function testBuildReturnsInstanceOfInvariableQueryMessage()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->build();
        $this->assertInstanceOf('Deicer\Pubsub\MessageInterface', $built);
    }

    public function testBuildUsesSetTopic()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->build();
        $this->assertSame('foo', $built->getTopic());
    }

    public function testBuildUsesSetContent()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->build();
        $this->assertSame('bar', $built->getContent());
    }

    public function testBuildUsesSetPublisher()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->build();
        $this->assertSame($this->mockPublisher, $built->getPublisher());
    }

    public function testBuildUsesSetAttributes()
    {
        $built = $this->fixture
            ->withTopic('foo')
            ->withContent('bar')
            ->withPublisher($this->mockPublisher)
            ->withAttributes($this->attributes)
            ->build();
        $this->assertSame($this->attributes, $built->getAttributes());
    }
}

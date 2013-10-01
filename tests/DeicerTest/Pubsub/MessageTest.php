<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Pubsub;

use Deicer\Pubsub\Message;
use DeicerTest\Framework\TestCase;

/**
 * Pubsub Message tests
 *
 * @category   DeicerTest
 * @package    Pubsub
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class MessageTest extends TestCase
{
    public $publisher;

    public function setUp()
    {
        $this->publisher = $this->getMock('Deicer\Pubsub\PublisherInterface');
    }

    public function testConstructInternalisesTopic()
    {
        $fixture = new Message('foo', null, $this->publisher);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructInternalisesContent()
    {
        $fixture = new Message('foo', 'bar', $this->publisher);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructInternalisesPublisher()
    {
        $fixture = new Message('foo', 'bar', $this->publisher);
        $this->assertSame($this->publisher, $fixture->getPublisher());
    }

    public function testConstructiWithEmptyTopicThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Message('', null, $this->publisher);
    }

    public function testConstructiWithNonStringTopicThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Message(new \stdClass(), null, $this->publisher);
    }

    public function testConstructWithAttributesArrayContainingNonSringKeyThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $attributes = array (
            'foo' => 'bar',
            1     => 'qux',
        );
        new Message('foo', null, $this->publisher, $attributes);
    }

    public function testConstructWithAttributesArrayContainingEmptyKeyThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $attributes = array (
            'foo' => 'bar',
            ''    => 'qux',
        );
        new Message('foo', null, $this->publisher, $attributes);
    }

    public function testConstructWithAttributesArrayContainingNonScalarValueThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $attributes = array (
            'foo'    => 'bar',
            'baz'    => new \stdClass(),
            'foobar' => 'foobaz',
        );
        new Message('foo', null, $this->publisher, $attributes);
    }

    public function testConstructInternalisesAttributes()
    {
        $attributes = array (
            'foo'    => 'bar',
            'baz'    => 'qux',
            'foobar' => 'foobaz',
        );
        $fixture = new Message('foo', null, $this->publisher, $attributes);
        $this->assertSame($attributes, $fixture->getAttributes());
    }

    public function testGetAttributeWithEmptyThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $fixture = new Message('foo', null, $this->publisher);
        $fixture->getAttribute('');
    }

    public function testGetAttributeWithNonStringThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $fixture = new Message('foo', null, $this->publisher);
        $fixture->getAttribute(array ());
    }

    public function testGetAttributeWithNonExistentAttributeReturnsNull()
    {
        $fixture = new Message('foo', null, $this->publisher, array ('foo' => 'bar'));
        $this->assertNull($fixture->getAttribute('baz'));
    }

    public function testGetAttributeReturnsRequestedAttribute()
    {
        $fixture = new Message('foo', null, $this->publisher, array ('foo' => 'bar'));
        $this->assertSame('bar', $fixture->getAttribute('foo'));
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $content = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '^Topic: "foobar" \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . ' \| ';
        $regex .= 'Publisher: (.)+PublisherInterface(.)+$';

        $fixture = new Message(
            'foobar',
            $content,
            $this->publisher
        );

        $this->assertRegExp('/' . $regex . '/', (string) $fixture);
    }
}

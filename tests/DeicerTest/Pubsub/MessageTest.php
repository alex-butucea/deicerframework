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
use \PHPUnit_Framework_TestCase as TestCase;

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

    public function fixtureFactory($topic, $content, $publisher)
    {
        return new Message($topic, $content, $publisher);
    }

    public function testConstructorInternalisesTopic()
    {
        $fixture = $this->fixtureFactory('foo', null, $this->publisher);
        $this->assertSame('foo', $fixture->getTopic());
    }

    public function testConstructorInternalisesContent()
    {
        $fixture = $this->fixtureFactory('', 'bar', $this->publisher);
        $this->assertSame('bar', $fixture->getContent());
    }

    public function testConstructorInternalisesPublisher()
    {
        $fixture = $this->fixtureFactory('', 'bar', $this->publisher);
        $this->assertSame($this->publisher, $fixture->getPublisher());
    }

    public function testConstructorTopicTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixtureFactory(null, null, $this->publisher);
        $this->fixtureFactory(1234, null, $this->publisher);
        $this->fixtureFactory(array (), null, $this->publisher);
        $this->fixtureFactory(new \stdClass(), null, $this->publisher);
    }
}

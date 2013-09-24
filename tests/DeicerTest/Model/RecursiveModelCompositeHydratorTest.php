<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Model;

use Deicer\Model\RecursiveModelCompositeHydrator;
use DeicerTest\Model\TestableModel;
use DeicerTest\Model\TestableModelComposite;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Model Hydrator unit test suite
 *
 * @category   DeicerTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class RecursiveModelCompositeHydratorTest extends TestCase
{
    public $fixture;

    public function setUp()
    {
        $this->fixture = new RecursiveModelCompositeHydrator(
            new TestableModel,
            new TestableModelComposite
        );
    }

    public function testSetModelPrototypeImplementsFluentInterface()
    {
        $this->assertSame(
            $this->fixture,
            $this->fixture->setModelPrototype(new TestableModel)
        );
    }

    public function testSetModelCompositeImplementsFluentInterface()
    {
        $this->assertSame(
            $this->fixture,
            $this->fixture->setModelComposite(new TestableModelComposite)
        );
    }

    public function testExchangeArrayWithAssociativeArrayThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->fixture->exchangeArray(
            array (
                'one' => 'foo',
                'two' => 'bar',
                '3'   => 'baz',
            )
        );
    }

    public function testExchangeArrayWithIndexedArrayOfNonArraysThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->fixture->exchangeArray(
            array (
                'foo',
                1234,
                new \stdClass(),
            )
        );
    }

    public function testExchangeArrayWithIndexedArrayOfIndexedArraysThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->fixture->exchangeArray(
            array (
                array (
                    'foo',
                    1234,
                    new \stdClass(),
                ),
            )
        );
    }

    public function testExchangeArrayWithEmptyArrayReturnsEmptyModelComposite()
    {
        $actual = $this->fixture->exchangeArray(array ());
        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(0, $actual->count());
    }

    public function testExchangeArrayWithIndexedArrayOfAssociativeArraysHydratesModelComposite()
    {
        $actual = $this->fixture->exchangeArray(
            array (
                array (
                    'id'         => 1,
                    'name'       => 'foo',
                    'categories' => array ('baz', 'qux'),
                ),
                array (
                    'id'         => 2,
                    'name'       => 'bar',
                    'categories' => array ('foobar', 'quux'),
                ),
            )
        );

        $this->assertInstanceOf('Deicer\Model\ModelCompositeInterface', $actual);
        $this->assertSame(2, $actual->count());

        $first  = $actual->offsetGet(0);
        $second = $actual->offsetGet(1);

        $this->assertInstanceOf('Deicer\Model\ModelInterface', $first);
        $this->assertSame(1, $first->id);
        $this->assertSame('foo', $first->name);
        $this->assertSame(array ('baz', 'qux'), $first->categories);

        $this->assertInstanceOf('Deicer\Model\ModelInterface', $second);
        $this->assertSame(2, $second->id);
        $this->assertSame('bar', $second->name);
        $this->assertSame(array ('foobar', 'quux'), $second->categories);
    }

    public function testExchangeArrayWithArraysContainingInvalidModelPropertiesRethrowsException()
    {
        try {
            $this->fixture->exchangeArray(
                array (
                    array (
                        'id'       => 1,
                        'name'     => 'foo',
                        'category' => 'bar',
                    ),
                )
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertInstanceOf('\OutOfBoundsException', $e->getPrevious());
            return;
        }

        $this->fail('Fixture failed to throw expected exception');
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Model;

use Deicer\Model\ModelInterface;
use DeicerTest\Framework\TestCase;

/**
 * Abstract Deicer Model Composite unit test suite
 * 
 * @category   DeicerTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class AbstractModelCompositeTest extends TestCase
{
    public $validExchangeArrayArg;
    public $invalidExchangeArrayArg;
    public $mixedExchangeArrayArg;

    public function setUp()
    {
        $first  = $this->getMock('Deicer\Model\ModelInterface');
        $second = $this->getMock('Deicer\Model\ModelInterface');
        $third  = $this->getMock('Deicer\Model\ModelInterface');

        $first->expects($this->any())
              ->method('__get')
              ->with($this->equalTo('name'))
              ->will($this->returnValue('first'));
        $second->expects($this->any())
              ->method('__get')
              ->with($this->equalTo('name'))
              ->will($this->returnValue('second'));
        $third->expects($this->any())
              ->method('__get')
              ->with($this->equalTo('name'))
              ->will($this->returnValue('third'));

        $this->validExchangeArrayArg = array (
            $first,
            $second,
            $third,
        );

        $this->invalidExchangeArrayArg = array (
            123,
            'foobar',
            array (),
            new \stdClass(),
        );

        $this->mixedExchangeArrayArg = array (
            123,
            $first,
            'foobar',
            $third,
            array (),
            new \stdClass(),
        );
    }

    public function testExchangeArrayWithMixedIndexArrayInternalisesModelsReindexed()
    {
        $data = array (
            'first'  => $this->validExchangeArrayArg[0],
            123      => $this->validExchangeArrayArg[1],
            'foobar' => $this->validExchangeArrayArg[2],
        );
        $fixture = new TestableModelComposite();
        $fixture->exchangeArray($data);

        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(0));
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(1));
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(2));
        $this->assertSame('first', $fixture->offsetGet(0)->name);
        $this->assertSame('second', $fixture->offsetGet(1)->name);
        $this->assertSame('third', $fixture->offsetGet(2)->name);
    }

    public function testExchangeArrayWithMixedArrayThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonInstanceException');
        $fixture = new TestableModelComposite();
        $fixture->exchangeArray($this->mixedExchangeArrayArg);
    }

    public function testTryExchangeArrayWithValidDataInternalisesAllModels()
    {
        $fixture = new TestableModelComposite();
        $fixture->tryExchangeArray($this->validExchangeArrayArg);

        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(0));
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(1));
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(2));
        $this->assertSame('first', $fixture->offsetGet(0)->name);
        $this->assertSame('second', $fixture->offsetGet(1)->name);
        $this->assertSame('third', $fixture->offsetGet(2)->name);
    }

    public function testTryExchangeArrayWithMixedDataOnlyInternalisesValidModels()
    {
        $fixture = new TestableModelComposite();
        $fixture->tryExchangeArray($this->mixedExchangeArrayArg);

        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(0));
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(1));
        $this->assertSame('first', $fixture->offsetGet(0)->name);
        $this->assertSame('third', $fixture->offsetGet(1)->name);
    }

    public function testExchangeArrayFiltersThroughOnExchangeArray()
    {
        $fixture = new TestableModelCompositeWithValidOnExchangeArray();
        $fixture->exchangeArray($this->validExchangeArrayArg);
        $this->assertSame(2, $fixture->count());
    }

    public function testInvalidOnExchangeArrayReturnTypeThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonArrayException');
        $fixture = new TestableModelCompositeWithInvalidOnExchangeArray();
        $fixture->exchangeArray($this->validExchangeArrayArg);
    }

    public function testCountReturnsZeroWhenInstanceIsEmpty()
    {
        $fixture = new TestableModelComposite();
        $this->assertCount(0, $fixture);
    }

    public function testCountReflectsArraySize()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertCount(3, $fixture);
    }

    public function testCurrentReflectsArrayPointer()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->current());
        $this->assertSame('first', $fixture->current()->name);
    }

    public function testCurrentThrowsExceptionOnInvalidIndex()
    {
        $this->setExpectedException('\OutOfRangeException');
        $fixture = new TestableModelComposite();
        $fixture->current();
    }

    public function testKeyReflectsArrayPointer()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertSame(0, $fixture->key());
    }

    public function testKeyThrowsExceptionOnInvalidIndex()
    {
        $this->setExpectedException('\OutOfRangeException');
        $fixture = new TestableModelComposite();
        $fixture->key();
    }

    public function testNextImplementsFluentInterface()
    {
        $fixture = new TestableModelComposite();
        $this->assertSame($fixture, $fixture->next());
    }

    public function testNextAdvancesArrayPointerOneIndex()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->next();
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->current());
        $this->assertSame('second', $fixture->current()->name);
    }

    public function testRewindImplementsFluentInterface()
    {
        $fixture = new TestableModelComposite();
        $this->assertSame($fixture, $fixture->rewind());
    }

    public function testRewindResetsArrayPointerToFirstIndex()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->next();
        $fixture->rewind();
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->current());
        $this->assertSame('first', $fixture->current()->name);
    }

    public function testValidReturnsFalseWhenInstanceIsEmpty()
    {
        $fixture = new TestableModelComposite();
        $this->assertFalse($fixture->valid());
    }

    public function testValidReturnsTrueWhenArrayPointerIsInRange()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertTrue($fixture->valid());
    }

    public function testValidReturnsFalseWhenArrayPointerIsOutOfRange()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->next()->next()->next();
        $this->assertFalse($fixture->valid());
    }

    public function testOffsetExistsWithNonIntOffsetThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonIntException');
        $fixture = new TestableModelComposite();
        $fixture->offsetExists('foobar');
        $fixture->offsetExists(array ());
        $fixture->offsetExists(new \stdClass());
        $fixture->offsetExists(true);
    }

    public function testOffsetExistsWithInRangeOffsetReturnsTrue()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertTrue($fixture->offsetExists(0));
        $this->assertTrue($fixture->offsetExists(1));
        $this->assertTrue($fixture->offsetExists(2));
    }

    public function testOffsetExistsWithOutOfRangeOffsetReturnsFalse()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertFalse($fixture->offsetExists(3));
    }

    public function testOffsetGetWithNonIntOffsetThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonIntException');
        $fixture = new TestableModelComposite();
        $fixture->offsetGet('foobar');
        $fixture->offsetGet(array ());
        $fixture->offsetGet(new \stdClass());
        $fixture->offsetGet(true);
    }

    public function testOffsetGetWithOutOfRangeOffsetThrowsException()
    {
        $this->setExpectedException('\OutOfRangeException');
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->offsetGet(3);
    }

    public function testOffsetGetWithInRangeOffsetReturnsCorrectModel()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(1));
        $this->assertSame('second', $fixture->offsetGet(1)->name);
    }

    public function testOffsetSetWithNonIntOffsetThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonIntException');
        $fixture = new TestableModelComposite();
        $fixture->offsetSet('foobar', $this->validExchangeArrayArg[0]);
        $fixture->offsetSet(array (), $this->validExchangeArrayArg[0]);
        $fixture->offsetSet(new \stdClass(), $this->validExchangeArrayArg[0]);
        $fixture->offsetSet(true, $this->validExchangeArrayArg[0]);
    }

    public function testOffsetSetWithNonModelInstanceThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonInstanceException');
        $fixture = new TestableModelComposite();
        $fixture->offsetSet(0, 'foobar');
        $fixture->offsetSet(1, array ());
        $fixture->offsetSet(2, new \stdClass());
        $fixture->offsetSet(3, true);
    }

    public function testOffsetSetWithValidArgumentsInternalisesModelAtCorrectIndex()
    {
        $fixture = new TestableModelComposite();
        $fixture->offsetSet(2, $this->validExchangeArrayArg[2]);
        $this->assertInstanceOf('Deicer\Model\ModelInterface', $fixture->offsetGet(2));
        $this->assertSame('third', $fixture->offsetGet(2)->name);
    }

    public function testOffsetUnsetWithNonIntOffsetThrowsException()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonIntException');
        $fixture = new TestableModelComposite();
        $fixture->offsetUnset('foobar');
        $fixture->offsetUnset(array ());
        $fixture->offsetUnset(new \stdClass());
        $fixture->offsetUnset(true);
    }

    public function testOffsetUnsetWithOutOfRangeOffsetThrowsException()
    {
        $this->setExpectedException('\OutOfRangeException');
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->offsetUnset(3);
    }

    public function testOffsetUnsetWithInRangeOffsetUnsetsModelAtCorrectIndex()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->offsetUnset(1);
        $this->assertFalse($fixture->offsetExists(1));
    }

    public function testSpawnReturnsNewInstance()
    {
        $fixture = new TestableModelComposite();
        $this->assertInstanceOf('DeicerTest\Model\TestableModelComposite', $fixture->spawn());
    }

    public function testGetArrayCopyReturnsInternalisedModelSet()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $this->assertSame($this->validExchangeArrayArg, $fixture->getArrayCopy());
    }

    public function testClearDiscardsInternalisedModelSet()
    {
        $fixture = new TestableModelComposite($this->validExchangeArrayArg);
        $fixture->clear();
        $this->assertSame(0, $fixture->count());
    }

    public function testClearImplementsFluentInterface()
    {
        $fixture = new TestableModelComposite();
        $this->assertSame($fixture, $fixture->clear());
    }
}

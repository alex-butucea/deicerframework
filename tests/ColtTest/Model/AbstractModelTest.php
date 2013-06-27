<?php

namespace ColtTest\Model;

/**
 * Abstract Colt Model unit test suite
 * 
 * @category   ColtTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    protected $validExchangeArrayArg = array (
        'id'         => 456,
        'name'       => 'quux',
        'categories' => array (
            4 => 'food',
            5 => 'shopping',
            6 => 'clothes',
        ),
    );

    protected $invalidData = array (
        'id'         => 789,
        'postcode'   => 'quux',
        'categories' => array (
            7 => 'events',
        ),
    );

    public function testAccessingNonexistentPropertyThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $model = new TestableModel();
        $model->foobar;
    }

    public function testInjectingAdditionalPropertyThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $model = new TestableModel();
        $model->test = 'foobar';
    }

    public function testUnsettingExistingPropertyThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $model = new TestableModel();
        unset($model->test);
    }

    public function testExchangeArrayWithEmptyArrayDoesntModifyProperties()
    {
        $model = new TestableModel();
        $model->id   = 100;
        $model->name = 'Test Name';
        $model->categories = array (
            10 => 'test one',
            11 => 'test two',
            12 => 'test three',
        );
        $model->exchangeArray(array ());

        $this->assertSame($model->id, 100);
        $this->assertSame($model->name, 'Test Name');
        $this->assertSame(
            $model->categories,
            array (
                10 => 'test one',
                11 => 'test two',
                12 => 'test three',
            )
        );
    }

    public function testExchangeArraySetsProperties()
    {
        $model = new TestableModel();
        $model->exchangeArray($this->validExchangeArrayArg);

        $this->assertSame($model->id, 456);
        $this->assertSame($model->name, 'quux');
        $this->assertSame(
            $model->categories,
            array (
                4 => 'food',
                5 => 'shopping',
                6 => 'clothes',
            )
        );
    }

    public function testExchangeArrayFiltersThroughOnExchangeArray()
    {
        $model = new TestableModelWithValidOnExchangeArray();
        $data  = array_change_key_case($this->validExchangeArrayArg, CASE_UPPER);
        $model->exchangeArray($data);
        
        $this->assertSame($model->id, 456);
        $this->assertSame($model->name, 'quux');
        $this->assertSame(
            $model->categories,
            array (
                4 => 'food',
                5 => 'shopping',
                6 => 'clothes',
            )
        );
    }

    public function testTryExchangeArraySetsProperties()
    {
        $model = new TestableModel();
        $model->tryExchangeArray($this->validExchangeArrayArg);

        $this->assertSame($model->id, 456);
        $this->assertSame($model->name, 'quux');
        $this->assertSame(
            $model->categories,
            array (
                4 => 'food',
                5 => 'shopping',
                6 => 'clothes',
            )
        );
    }

    public function testTryExchangeArraySkipsInvalidProperties()
    {
        $model = new TestableModel();
        $model->tryExchangeArray($this->invalidData);

        $this->assertSame($model->id, 789);
        $this->assertSame($model->name, '');
        $this->assertSame(
            $model->categories,
            array (
                7 => 'events',
            )
        );
    }

    public function testConstructorProxiesToExchangeArray()
    {
        $model = new TestableModel($this->validExchangeArrayArg);

        $this->assertSame($model->id, 456);
        $this->assertSame($model->name, 'quux');
        $this->assertSame(
            $model->categories,
            array (
                4 => 'food',
                5 => 'shopping',
                6 => 'clothes',
            )
        );
    }

    public function testInvalidOnExchangeArrayReturnTypeThrowsException()
    {
        $this->setExpectedException('Colt\Exception\Type\NonArrayException');
        $model = new TestableModelWithInvalidOnExchangeArray();
        $model->exchangeArray($this->validExchangeArrayArg);
    }

    public function testCloneReturnsDeepObjectCopy()
    {
        $original = new TestableModel(
            array (
                'name'  => 'original',
                'child' => new TestableModel(
                    array (
                        'name' => 'original child'
                    )
                )
            )
        );
        $clone = clone $original;

        $this->assertSame($original->name, $clone->name);
        $this->assertSame($original->child->name, $clone->child->name);
        
        // Child object is no longer a reference to original
        $original->child->name = 'new child';
        $this->assertNotSame($original->child->name, $clone->child->name);
    }
}

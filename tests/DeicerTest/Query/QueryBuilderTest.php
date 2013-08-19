<?php

namespace DeicerTest\Query;

use Deicer\Query\QueryBuilder;

/**
 * Deicer Query Builder unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public $fixture;
    public $model;
    public $modelComposite;

    public function setUp()
    {
        $this->fixture = new QueryBuilder(
            'DeicerTest\Query'
        );

        $this->model = $this->getMock(
            'Deicer\Model\ModelInterface',
            array (
                '__construct',
                '__unset',
                '__get',
                '__set',
                'exchangeArray',
                'tryExchangeArray',
                'getArrayCopy',
                'clear',
            )
        );

        $this->composite = $this->getMock(
            'Deicer\Model\ModelCompositeInterface',
            array (
                '__construct',
                'exchangeArray',
                'tryExchangeArray',
                'getArrayCopy',
                'clear',
                'current',
                'next',
                'key',
                'valid',
                'rewind',
                'count',
                'offsetExists',
                'offsetGet',
                'offsetSet',
                'offsetUnset',
            )
        );
    }

    public function testWithDataProviderImplementsFluentInterface()
    {
        $actual = $this->fixture->withDataProvider(new \stdClass());
        $this->assertSame($actual, $this->fixture);
    }

    public function testWithModelPrototypeImplementsFluentInterface()
    {
        $actual = $this->fixture->withModelPrototype($this->model);
        $this->assertSame($actual, $this->fixture);
    }

    public function testWithModelCompositePrototypeImplementsFluentInterface()
    {
        $actual = $this->fixture->withModelCompositePrototype($this->composite);
        $this->assertSame($actual, $this->fixture);
    }

    public function testConstructNamespaceTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new QueryBuilder(array ());
    }

    public function testBuildClassnameTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build(1234);
    }

    public function testBuildThrowsExceptionIfModelPrototypeInUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->build('DeicerTest\Query\TestableInvariableQueryWithValidFetchData');
    }

    public function testBuildThrowsExceptionIfModelCompositePrototypeInUnset()
    {
        $this->setExpectedException('LogicException');
        $this->fixture
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableInvariableQueryWithValidFetchData');
    }

    public function testBuildThrowsExceptionIfClassnameIsEmpty()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('');
    }

    public function testBuildThrowsExceptionIfClassDoesntExist()
    {
        $this->setExpectedException('Deicer\Exception\NonExistentClassException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('\Foo\Bar');
    }

    public function testBuildThrowsExceptionIfClassDoesntImplementRecognisedInterface()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonInstanceException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\FakeQuery');
    }

    public function testBuildConstructsInvariableQueryWithAppropriateDependencies()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableInvariableQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTest\Query\TestableInvariableQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildConstructsTokenizedQueryWithAppropriateDependencies()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableTokenizedQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTest\Query\TestableTokenizedQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildConstructsParametizedQueryWithAppropriateDependencies()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableParametizedQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTest\Query\TestableParametizedQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildUsesSetModelPrototype()
    {
        $this->model
            ->expects($this->atLeastOnce())
            ->method('exchangeArray');

        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableInvariableQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTest\Query\TestableInvariableQueryWithValidFetchData',
            $actual
        );

        $actual->execute();
    }

    public function testBuildUsesSetModelCompositePrototype()
    {
        $this->composite
            ->expects($this->atLeastOnce())
            ->method('exchangeArray');

        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTest\Query\TestableInvariableQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTest\Query\TestableInvariableQueryWithValidFetchData',
            $actual
        );
    }
}

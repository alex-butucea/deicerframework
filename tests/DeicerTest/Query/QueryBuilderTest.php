<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use stdClass;
use Deicer\Query\QueryBuilder;
use DeicerTest\Framework\TestCase;

/**
 * Deicer Query Builder tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class QueryBuilderTest extends TestCase
{
    public $fixture;
    public $model;
    public $modelComposite;

    public function setUp()
    {
        $this->fixture = new QueryBuilder(
            'DeicerTestAsset\Query'
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
        $actual = $this->fixture->withDataProvider(new stdClass());
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
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        new QueryBuilder(array ());
    }

    public function testBuildClassnameTypeStrength()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build(1234);
    }

    public function testBuildThrowsExceptionIfModelPrototypeInUnset()
    {
        $this->setExpectedException(
            'Deicer\Query\Exception\MissingModelPrototypeException'
        );
        $this->fixture
            ->withModelCompositePrototype($this->composite)
            ->build('DeicerTestAsset\Query\TestableInvariableQueryWithValidFetchData');
    }

    public function testBuildThrowsExceptionIfModelCompositePrototypeInUnset()
    {
        $this->setExpectedException(
            'Deicer\Query\Exception\MissingModelCompositePrototypeException'
        );
        $this->fixture
            ->withModelPrototype($this->model)
            ->build('DeicerTestAsset\Query\TestableInvariableQueryWithValidFetchData');
    }

    public function testBuildThrowsExceptionIfClassnameIsEmpty()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidArgumentException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('');
    }

    public function testBuildThrowsExceptionIfClassDoesntExist()
    {
        $this->setExpectedException('Deicer\Query\Exception\NonExistentQueryException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('\Foo\Bar');
    }

    public function testBuildThrowsExceptionIfClassDoesntImplementRecognisedInterface()
    {
        $this->setExpectedException('Deicer\Query\Exception\InvalidQueryInterfaceException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('FakeQuery');
    }

    public function testBuildThrowsExceptionIfQueryDependsOnDataProviderAndDataProviderIsUnset()
    {
        $this->setExpectedException('Deicer\Query\Exception\MissingDataProviderException');
        $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableInvariableQueryWithDataProviderDependency');
    }

    public function testBuildCanConstructInvariableQuery()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableInvariableQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTestAsset\Query\TestableInvariableQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildCanConstructSlugizedQuery()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableSlugizedQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTestAsset\Query\TestableSlugizedQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildCanConstructParameterizedQuery()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableParameterizedQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTestAsset\Query\TestableParameterizedQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildCanConstructIdentifiedQuery()
    {
        $actual = $this->fixture
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableIdentifiedQueryWithValidFetchData');

        $this->assertInstanceOf(
            'DeicerTestAsset\Query\TestableIdentifiedQueryWithValidFetchData',
            $actual
        );
    }

    public function testBuildCanConstructDataProviderDependantQuery()
    {
        $actual = $this->fixture
            ->withDataProvider(new stdClass())
            ->withModelPrototype($this->model)
            ->withModelCompositePrototype($this->composite)
            ->build('TestableInvariableQueryWithDataProviderDependency');

        $this->assertInstanceOf(
            'DeicerTestAsset\Query\TestableInvariableQueryWithDataProviderDependency',
            $actual
        );
    }
}

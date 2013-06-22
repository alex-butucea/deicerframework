<?php

namespace ColtTest\Exception;

use Colt\Exception\Type\NonIntException;

/**
 * Colt Int Type Exception unit test suite
 * 
 * @category   ColtTest
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonIntExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorDefaultsMessage()
    {
        $fixture = new NonIntException();
        $this->assertSame(NonIntException::MESSAGE, $fixture->getMessage());
    }

    public function testConstructorDefaultsCode()
    {
        $fixture = new NonIntException();
        $this->assertSame(NonIntException::CODE, $fixture->getCode());
    }

    public function testConstructorSetsMessage()
    {
        $fixture = new NonIntException('foobar');
        $this->assertSame('foobar', $fixture->getMessage());
    }

    public function testConstructorSetsCode()
    {
        $fixture = new NonIntException(null, 1234);
        $this->assertSame(1234, $fixture->getCode());
    }
}

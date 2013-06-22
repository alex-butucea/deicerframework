<?php

namespace ColtTest\Exception;

use Colt\Exception\Type\NonFloatException;

/**
 * Colt Float Type Exception unit test suite
 * 
 * @category   ColtTest
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonFloatExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorDefaultsMessage()
    {
        $fixture = new NonFloatException();
        $this->assertSame(NonFloatException::MESSAGE, $fixture->getMessage());
    }

    public function testConstructorDefaultsCode()
    {
        $fixture = new NonFloatException();
        $this->assertSame(NonFloatException::CODE, $fixture->getCode());
    }

    public function testConstructorSetsMessage()
    {
        $fixture = new NonFloatException('foobar');
        $this->assertSame('foobar', $fixture->getMessage());
    }

    public function testConstructorSetsCode()
    {
        $fixture = new NonFloatException(null, 1234);
        $this->assertSame(1234, $fixture->getCode());
    }
}

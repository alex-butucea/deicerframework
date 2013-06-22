<?php

namespace ColtTest\Exception;

use Colt\Exception\Type\NonInstanceException;

/**
 * Colt Instance Type Exception unit test suite
 * 
 * @category   ColtTest
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonInstanceExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorDefaultsMessage()
    {
        $fixture = new NonInstanceException();
        $this->assertSame(NonInstanceException::MESSAGE, $fixture->getMessage());
    }

    public function testConstructorDefaultsCode()
    {
        $fixture = new NonInstanceException();
        $this->assertSame(NonInstanceException::CODE, $fixture->getCode());
    }

    public function testConstructorSetsMessage()
    {
        $fixture = new NonInstanceException('foobar');
        $this->assertSame('foobar', $fixture->getMessage());
    }

    public function testConstructorSetsCode()
    {
        $fixture = new NonInstanceException(null, 1234);
        $this->assertSame(1234, $fixture->getCode());
    }
}

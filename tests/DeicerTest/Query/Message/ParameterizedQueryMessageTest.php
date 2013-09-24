<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\ParameterizedQueryMessage;
use DeicerTest\Query\Message\AbstractQueryMessageTest;

/**
 * Deicer Parameterized Query Message tests
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ParameterizedQueryMessageTest extends AbstractQueryMessageTest
{
    public $params = array (
        'foo' => 'bar',
        'baz' => 'qux',
    );

    public function setUp()
    {
        $this->publisher = $this->getMock('Deicer\Query\ParameterizedQueryInterface');
    }

    public function fixtureFactory($topic, $content, $publisher)
    {
        return new ParameterizedQueryMessage($topic, $content, $publisher, array ());
    }

    public function testConstructorInternalisesParams()
    {
        $fixture = new ParameterizedQueryMessage('foo', null, $this->publisher, $this->params);
        $this->assertSame($this->params, $fixture->getParams());
    }

    public function testGetParamWithValidNameReturnsCorrectParam()
    {
        $fixture = new ParameterizedQueryMessage('foo', null, $this->publisher, $this->params);
        $this->assertSame('bar', $fixture->getParam('foo'));
        $this->assertSame('qux', $fixture->getParam('baz'));
    }

    public function testGetParamWithInvalidNameThrowsException()
    {
        $this->setExpectedException('\OutOfBoundsException');
        $fixture = new ParameterizedQueryMessage('foo', null, $this->publisher, $this->params);
        $fixture->getParam('foobar');
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $content = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));
        $params  = array ('foobar' => 'foobaz', 'quux' => 1234);

        $regex  = '/^Parameterized Query Execution: (.)+ParameterizedQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_data_fetch" \| ';
        $regex .= 'Elapsed Time: 890ms \| ';
        $regex .= 'Params: ' . preg_quote(json_encode($params)) . ' \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new ParameterizedQueryMessage(
            'failure_data_fetch',
            $content,
            $this->publisher,
            $params
        );
        $fixture->addElapsedTime(890);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

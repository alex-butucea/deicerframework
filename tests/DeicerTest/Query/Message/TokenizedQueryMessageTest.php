<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\TokenizedQueryMessage;
use DeicerTest\Query\Message\AbstractQueryMessageTest;

/**
 * Deicer Tokenized Query Message unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TokenizedQueryMessageTest extends AbstractQueryMessageTest
{
    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\TokenizedQueryInterface');
    }

    public function fixtureFactory($topic, $content, $publisher)
    {
        return new TokenizedQueryMessage($topic, $content, $publisher, '');
    }

    public function testConstructorInternalisesToken()
    {
        $fixture = new TokenizedQueryMessage('foo', null, $this->mockQuery, 'bar');
        $this->assertSame('bar', $fixture->getToken());
    }

    public function testConstructorTokenTypeStrength()
    {
        $this->setExpectedException('Deicer\Exception\Type\NonStringException');
        new TokenizedQueryMessage('', null, $this->mockQuery, null);
        new TokenizedQueryMessage('', null, $this->mockQuery, 1234);
        new TokenizedQueryMessage('', null, $this->mockQuery, array ());
        new TokenizedQueryMessage('', null, $this->mockQuery, new \stdClass());
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $content = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '/^Tokenized Query Execution: (.)+TokenizedQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_data_type" \| ';
        $regex .= 'Elapsed Time: 567ms \| ';
        $regex .= 'Token: "foobar" \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new TokenizedQueryMessage(
            'failure_data_type',
            $content,
            $this->mockQuery,
            'foobar'
        );
        $fixture->addElapsedTime(567);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

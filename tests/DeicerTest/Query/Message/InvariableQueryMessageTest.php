<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query\Message;

use Deicer\Query\Message\InvariableQueryMessage;
use DeicerTest\Query\Message\AbstractQueryMessageTest;

/**
 * Deicer Invariable Query Message unit test suite
 * 
 * @category   DeicerTest
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvariableQueryMessageTest extends AbstractQueryMessageTest
{
    public function setUp()
    {
        $this->mockQuery = $this->getMock('Deicer\Query\InvariableQueryInterface');
    }

    public function fixtureFactory($topic, $content, $publisher)
    {
        return new InvariableQueryMessage($topic, $content, $publisher);
    }

    public function testToStringSerializesMessageStateCorrectly()
    {
        $content = array ('foo' => array ('bar' => 'baz', 'qux' => new \stdClass()));

        $regex  = '/^Invariable Query Execution: (.)+InvariableQueryInterface(.)+ \| ';
        $regex .= 'Result: "failure_model_hydrator" \| ';
        $regex .= 'Elapsed Time: 1234ms \| ';
        $regex .= 'Content: ' . preg_quote(json_encode($content)) . '$/';

        $fixture = new InvariableQueryMessage(
            'failure_model_hydrator',
            $content,
            $this->mockQuery
        );
        $fixture->addElapsedTime(1234);

        $this->assertRegExp($regex, (string) $fixture);
    }
}

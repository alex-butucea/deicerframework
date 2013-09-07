<?php

namespace Deicer\Query\Message;

use Deicer\Pubsub\MessageBuilderInterface;

/**
 * Marker interface for concrete Tokenized Query Message Builder
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenizedQueryMessageBuilderInterface extends
 MessageBuilderInterface
{
    /**
     * Set unique token to build with
     * 
     * @throws NonStringException If $token is not a string
     * @param  string $token Token to build with
     * @return TokenizedQueryMessageBuilderInterface Fluent interface
     */
    public function withToken($token);
}

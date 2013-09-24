<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Message;

use Deicer\Pubsub\MessageBuilderInterface;

/**
 * Marker interface for concrete Parameterized Query Message Builder
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Message
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParameterizedQueryMessageBuilderInterface extends
 MessageBuilderInterface
{
    /**
     * Set parameter set to build with
     * 
     * @param  array $params Parameters to build with
     * @return ParameterizedQueryMessageBuilderInterface Fluent interface
     */
    public function withParams(array $params);
}
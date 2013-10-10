<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Pubsub\Exception;

use OutOfRangeException as SplException;
use Deicer\Pubsub\Exception\ExceptionInterface;

/**
 * Deicer Pubsub Out of Range Exception
 *
 * @category   Deicer
 * @package    Pubsub
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class OutOfRangeException extends SplException implements ExceptionInterface
{
}

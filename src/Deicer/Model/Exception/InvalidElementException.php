<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model\Exception;

use InvalidArgumentException as SplException;
use Deicer\Model\Exception\ExceptionInterface;

/**
 * Deicer Hydrator Invalid Data Element Exception
 *
 * Thrown when an element in an indexed data array isn't an array
 *
 * @category   Deicer
 * @package    Model
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class InvalidElementException extends SplException implements ExceptionInterface
{
}

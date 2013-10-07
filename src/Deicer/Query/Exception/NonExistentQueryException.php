<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Exception;

use Deicer\Query\Exception\ExceptionInterface;
use Deicer\Exception\ExceptionInterface as DeicerExceptionInterface;

/**
 * Deicer Non Existent Query Exception
 *
 * Thrown by Query Builder when a non existent Query class is requested
 *
 * @category   Deicer
 * @package    Query 
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonExistentQueryException extends \InvalidArgumentException implements
    ExceptionInterface,
    DeicerExceptionInterface
{
}

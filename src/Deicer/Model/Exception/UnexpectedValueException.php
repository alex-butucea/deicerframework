<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model\Exception;

use UnexpectedValueException as SplException;
use Deicer\Model\Exception\ExceptionInterface;

/**
 * Deicer Model Unexpected Value Exception
 *
 * @category   Deicer
 * @package    Model
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class UnexpectedValueException extends SplException implements ExceptionInterface
{
}

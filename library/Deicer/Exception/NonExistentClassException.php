<?php

namespace Deicer\Exception;

use Deicer\Exception\ExceptionInterface;

/**
 * Deicer Non Existent Class Exception
 *
 * Thrown when a non existent class is requested by a factory or builder
 *
 * @category   Deicer
 * @package    Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonExistentClassException extends \LogicException implements
    ExceptionInterface
{
}

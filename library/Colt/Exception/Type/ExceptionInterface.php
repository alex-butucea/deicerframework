<?php

namespace Colt\Exception\Type;

use Colt\Exception\ExceptionInterface as ParentException;

/**
 * Colt Type Exception Marker Interface
 *
 * Provides a common marker interface for all type exceptions thrown by the framework.
 *
 * @category   Colt
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ExceptionInterface extends ParentException
{
}

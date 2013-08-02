<?php

namespace Deicer\Query\Exception;

use Deicer\Query\Exception\ExceptionInterface;
use Deicer\Exception\ExceptionInterface as DeicerExceptionInterface;

/**
 * Deicer Query Data Fetch Exception
 *
 * Represents the failure of a Query implementation to contain an exception
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class DataFetchException extends \Exception implements
    ExceptionInterface,
    DeicerExceptionInterface
{
}
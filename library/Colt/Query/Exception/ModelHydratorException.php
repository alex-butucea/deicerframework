<?php

namespace Colt\Query\Exception;

use Colt\Query\Exception\ExceptionInterface;
use Colt\Exception\ExceptionInterface as ColtExceptionInterface;

/**
 * Colt Query Hydrator Exception
 *
 * Represents the failure of a Query's Recursive Model Composite Hydrator to
 * hydrate a model composite using data returned by QueryInterface::fetchData()
 *
 * @category   Colt
 * @package    Query
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class ModelHydratorException extends \Exception implements
    ExceptionInterface,
    ColtExceptionInterface
{
}

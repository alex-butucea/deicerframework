<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query\Exception;

use LogicException as SplException;
use Deicer\Query\Exception\ExceptionInterface;

/**
 * Deicer Missing Data Provider Exception
 *
 * Thrown by query builder and queries implementing setDataProvider() on execute()
 * if query data provider hasn't been set
 *
 * @category   Deicer
 * @package    Query
 * @subpackage Exception
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class MissingDataProviderException extends SplException implements ExceptionInterface
{
}

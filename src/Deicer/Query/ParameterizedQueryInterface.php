<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Query;

use Deicer\Query\QueryInterface;
use Deicer\Stdlib\ParameterConsumerInterface;
use Deicer\Stdlib\ParameterProviderInterface;

/**
 * {@inheritdoc}
 *
 * Parameterized query used for fetching models using a set of key-value pairs.
 * Use cases would be retrieving several models from a collection using a
 * search algorithm with multiple filters.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ParameterizedQueryInterface extends
 QueryInterface,
 ParameterConsumerInterface,
 ParameterProviderInterface
{
    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @throws IncompatibleParamsException If $decoratable doesnt use same params
     * @param  ParameterizedQueryInterface $decoratable The query to decorate
     * @retrun ParameterizedQueryInterface Fluent interface
     */
    public function decorate(ParameterizedQueryInterface $decoratable);

    /**
     * Attempt to set multiple query parameters, skipping invalid params
     * 
     * @param  array $params Parameters to attempt to set
     * @retrun ParameterizedQueryInterface Fluent interface
     */
    public function trySetParams(array $params);
}

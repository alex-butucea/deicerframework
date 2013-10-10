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
use Deicer\Stdlib\IdentifierConsumerInterface;
use Deicer\Stdlib\IdentifierProviderInterface;

/**
 * Marker interface for identified queries
 *
 * Identified query used for fetching models using an integer id.
 * Use cases would be retrieving a unique / sigular model from a data store.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface IdentifiedQueryInterface extends
 QueryInterface,
 IdentifierConsumerInterface,
 IdentifierProviderInterface
{
    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  IdentifiedQueryInterface $decorable The query to decorate
     * @retrun IdentifiedQueryInterface Fluent interface
     */
    public function decorate(IdentifiedQueryInterface $decorable);
}

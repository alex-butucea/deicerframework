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

/**
 * {@inheritdoc}
 *
 * Invariable query used for fetching models using a fixed algorithm.
 * Use cases would be retrieving all models from a given collection or fetching
 * a unique / sigular model from a data store.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface InvariableQueryInterface extends QueryInterface
{
    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  InvariableQueryInterface $decorable The query to decorate
     * @retrun InvariableQueryInterface Fluent interface
     */
    public function decorate(InvariableQueryInterface $decorable);
}

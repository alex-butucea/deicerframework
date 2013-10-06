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
use Deicer\Stdlib\TokenConsumerInterface;
use Deicer\Stdlib\TokenProviderInterface;

/**
 * Marker interface for tokenized queries
 *
 * Tokenized query used for fetching models using an id, slug or other token.
 * Use cases would be retrieving a unique / sigular model from a data store or
 * all models matching a given tag / search term.
 *
 * @category   Deicer
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenizedQueryInterface extends
 QueryInterface,
 TokenConsumerInterface,
 TokenProviderInterface
{
    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  TokenizedQueryInterface $decoratable The query to decorate
     * @retrun TokenizedQueryInterface Fluent interface
     */
    public function decorate(TokenizedQueryInterface $decoratable);
}

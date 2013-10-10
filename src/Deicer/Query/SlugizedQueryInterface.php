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
use Deicer\Stdlib\SlugConsumerInterface;
use Deicer\Stdlib\SlugProviderInterface;

/**
 * Marker interface for slugized queries
 *
 * Slugized query used for fetching models using a unique slug.
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
interface SlugizedQueryInterface extends
 QueryInterface,
 SlugConsumerInterface,
 SlugProviderInterface
{
    /**
     * Decorate a query to implement a fallback on query execution failure
     *
     * @param  SlugizedQueryInterface $decorable The query to decorate
     * @retrun SlugizedQueryInterface Fluent interface
     */
    public function decorate(SlugizedQueryInterface $decorable);
}

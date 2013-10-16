<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Query;

use Deicer\Query\AbstractSlugizedQuery;

/**
 * Deicer Test Slugized Query
 *
 * Represents a concrete implementation of a Deicer Slugized Query with
 * an invalid implementation of fetchData() that returns a empty array
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableSlugizedQueryWithEmptyArrayReturningFetchData extends
 AbstractSlugizedQuery
{
    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        return array ();
    }
}

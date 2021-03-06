<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Query;

use Deicer\Query\AbstractIdentifiedQuery;

/**
 * Deicer Test Identified Query
 *
 * Represents a concrete implementation of a Deicer Identified Query with
 * an invalid implementation of fetchData() that returns a non array
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableIdentifiedQueryWithNonArrayReturningFetchData extends
 AbstractIdentifiedQuery
{
    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        return false;
    }
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Query;

use Exception;
use Deicer\Query\AbstractIdentifiedQuery;

/**
 * Deicer Test Identified Query
 *
 * Represents a concrete implementation of a Deicer Identified Query
 * with an implementation of fetchData() that throws an exception
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableIdentifiedQueryWithExceptionThrowingFetchData extends
 AbstractIdentifiedQuery
{
    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        throw new Exception(
            'foo',
            123,
            new Exception(
                'bar',
                456
            )
        );
    }
}

<?php

namespace DeicerTest\Query;

use Deicer\Query\AbstractParametizedQuery;

/**
 * Deicer Test Parametized Query
 *
 * Represents a concrete implementation of a Deicer Parametized Query
 * with an implementation of fetchData() that throws an exception
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableParametizedQueryWithExceptionThrowingFetchData extends
 AbstractParametizedQuery
{
    /**
     * {@inheritdoc}
     */
    protected $params = array (
        'genre'  => '',
        'year'   => 0,
        'author' => '',
    );

    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        throw new \Exception(
            'foo',
            123,
            new \Exception(
                'bar',
                456
            )
        );
    }
}

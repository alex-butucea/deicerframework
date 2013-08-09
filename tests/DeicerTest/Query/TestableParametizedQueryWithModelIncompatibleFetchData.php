<?php

namespace DeicerTest\Query;

use Deicer\Query\AbstractParametizedQuery;

/**
 * Deicer Test Parametized Query
 *
 * Represents a concrete implementation of a Deicer Parametized Query with
 * an implementation of fetchData() incompatible with model properties
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableParametizedQueryWithModelIncompatibleFetchData extends
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
        return array (
            array (
                'id'   => 1,
                'name' => 'foo',
                'role' => 'bar',
            ),
        );
    }
}

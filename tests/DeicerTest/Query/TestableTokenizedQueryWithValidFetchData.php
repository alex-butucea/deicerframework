<?php

namespace DeicerTest\Query;

use Deicer\Query\AbstractTokenizedQuery;

/**
 * Deicer Test Tokenized Query
 *
 * Represents a concrete implementation of a Deicer Tokenized Query
 * with an valid implementation of fetchData()
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableTokenizedQueryWithValidFetchData extends AbstractTokenizedQuery
{
    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        return array (
            array (
                'id'   => 1,
                'name' => 'foo',
            ),
            array (
                'id'   => 2,
                'name' => 'bar',
            ),
        );
    }
}

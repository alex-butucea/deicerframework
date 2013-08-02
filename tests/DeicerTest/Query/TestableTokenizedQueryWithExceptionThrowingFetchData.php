<?php

namespace DeicerTest\Query;

use Deicer\Query\AbstractTokenizedQuery;

/**
 * Deicer Test Tokenized Query
 *
 * Represents a concrete implementation of a Deicer Tokenized Query
 * with an implementation of fetchData() that throws an exception
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableTokenizedQueryWithExceptionThrowingFetchData extends
 AbstractTokenizedQuery
{
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

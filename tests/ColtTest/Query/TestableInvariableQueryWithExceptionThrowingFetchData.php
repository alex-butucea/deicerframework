<?php

namespace ColtTest\Query;

use Colt\Query\AbstractInvariableQuery;

/**
 * Colt Test Invariable Query
 *
 * Represents a concrete implementation of a Colt Invairable Query
 * with an implementation of fetchData() that throws an exception
 *
 * @category   ColtTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableInvariableQueryWithExceptionThrowingFetchData extends
 AbstractInvariableQuery
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

<?php

namespace ColtTest\Query;

use Colt\Query\AbstractInvariableQuery;

/**
 * Colt Test Invariable Query
 *
 * Represents a concrete implementation of a Colt Invairable Query
 * with an valid implementation of fetchData()
 *
 * @category   ColtTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableInvariableQueryWithValidFetchData extends AbstractInvariableQuery
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

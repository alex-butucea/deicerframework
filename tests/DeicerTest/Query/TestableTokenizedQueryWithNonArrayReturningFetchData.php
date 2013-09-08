<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Query;

use Deicer\Query\AbstractTokenizedQuery;

/**
 * Deicer Test Tokenized Query
 *
 * Represents a concrete implementation of a Deicer Tokenized Query with
 * an invalid implementation of fetchData() that returns a non array
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableTokenizedQueryWithNonArrayReturningFetchData extends
 AbstractTokenizedQuery
{
    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        return false;
    }
}

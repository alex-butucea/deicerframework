<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Query;

use Deicer\Query\AbstractParameterizedQuery;

/**
 * Deicer Test Parameterized Query
 *
 * Represents a concrete implementation of a Deicer Parameterized Query
 * with an valid implementation of fetchData()
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableParameterizedQueryWithValidFetchData extends AbstractParameterizedQuery
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
            ),
            array (
                'id'   => 2,
                'name' => 'bar',
            ),
        );
    }
}

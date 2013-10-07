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
 * with a set of param not compatible with other queries.
 * Used to test decorator API.
 *
 * @category   DeicerTest
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableParameterizedQueryWithIncompatibleParams extends AbstractParameterizedQuery
{
    /**
     * {@inheritdoc}
     */
    protected $params = array (
        'genre'  => '',
        'isbn'   => '',
        'author' => '',
    );

    /**
     * {@inheritdoc}
     */
    protected function fetchData()
    {
        return array ();
    }
}

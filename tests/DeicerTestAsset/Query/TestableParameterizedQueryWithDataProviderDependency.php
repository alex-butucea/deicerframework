<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Query;

use stdClass;
use Deicer\Query\AbstractParameterizedQuery;

/**
 * Deicer Test Data Provider Aware Query
 *
 * Represents a concrete implementation of a Deicer Parameterized Query
 * that requires a data provider for execution
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableParameterizedQueryWithDataProviderDependency extends AbstractParameterizedQuery
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

    /**
     * Set the data provider required to fetch data
     *
     * @param  stdClass $dataProvider Mock data provider
     * @return void
     */
    public function setDataProvider(stdClass $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }
}

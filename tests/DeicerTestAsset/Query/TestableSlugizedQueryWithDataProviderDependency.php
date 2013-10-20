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
use Deicer\Query\AbstractSlugizedQuery;

/**
 * Deicer Test Data Provider Aware Query
 *
 * Represents a concrete implementation of a Deicer Slugized Query
 * that requires a data provider for execution
 *
 * @category   DeicerTestAsset
 * @package    Query
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableSlugizedQueryWithDataProviderDependency extends AbstractSlugizedQuery
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

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTest\Model;

/**
 * Deicer Test Model
 *
 * Represents a concrete implementation of a Deicer Model with an valid implementation of onExchangeArray()
 *
 * @category   DeicerTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableModelWithValidOnExchangeArray extends TestableModel
{
    /**
     * Lowercases all keys
     *
     * @see Deicer\Model\AbstactModel::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        $filtered = array ();

        foreach ($values as $k => $v) {
            $filtered[strtolower($k)] = $v;
        }

        return $filtered;
    }
}

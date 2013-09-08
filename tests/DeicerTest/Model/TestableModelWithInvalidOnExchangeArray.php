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
 * Deicer Invalid Test Model
 *
 * Represents a concrete implementation of a Deicer Model with an invalid implementation of onExchangeArray()
 *
 * @category   DeicerTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableModelWithInvalidOnExchangeArray extends TestableModel
{
    /**
     * Returns unexpected type to test validation
     *
     * @see Deicer\Model\AbstactModel::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        return new \stdClass();
    }
}

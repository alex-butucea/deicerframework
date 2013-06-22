<?php

namespace ColtTest\Model;

/**
 * Colt Invalid Test Model
 *
 * Represents a concrete implementation of a Colt Model with an invalid implementation of onExchangeArray()
 *
 * @category   ColtTest
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
     * @see Colt\Model\AbstactModel::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        return new \stdClass();
    }
}

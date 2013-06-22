<?php

namespace ColtTest\Model;

use \Colt\Model\AbstractModelComposite;

/**
 * Colt Test Model Composite
 *
 * Represents a concrete implementation of a Colt Model Composite with an invalid implementation of onExchangeArray
 *
 * @category   ColtTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableModelCompositeWithInvalidOnExchangeArray extends AbstractModelComposite
{
    /**
     * Returns unexpected type to test validation
     *
     * @see Colt\Model\AbstactModelComposite::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        return new \stdClass();
    }
}

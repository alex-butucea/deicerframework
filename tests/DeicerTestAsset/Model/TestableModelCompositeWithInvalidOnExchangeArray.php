<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Model;

use stdClass;
use \Deicer\Model\AbstractModelComposite;

/**
 * Deicer Test Model Composite
 *
 * Represents a concrete implementation of a Deicer Model Composite
 * with an invalid implementation of onExchangeArray
 *
 * @category   DeicerTest
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
     * @see Deicer\Model\AbstactModelComposite::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        return new stdClass();
    }
}

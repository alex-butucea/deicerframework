<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace DeicerTestAsset\Model;

use Deicer\Model\AbstractModelComposite;

/**
 * Deicer Test Model Composite
 *
 * Represents a concrete implementation of a Deicer Model Composite
 * with a valid implementation of onExchangeArray
 *
 * @category   DeicerTestAsset
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableModelCompositeWithValidOnExchangeArray extends AbstractModelComposite
{
    /**
     * Pops the last model off the array
     *
     * @see Deicer\Model\AbstactModelComposite::onExchangeArray()
     */
    protected function onExchangeArray(array $values)
    {
        array_pop($values);
        return $values;
    }
}

<?php

namespace ColtTest\Model;

use \Colt\Model\AbstractModel;
use \Colt\Model\ModelInterface;

/**
 * Colt Test Model
 *
 * Represents a generic concrete implementation of a Colt Model
 *
 * @category   ColtTest
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class TestableModel extends AbstractModel
{
    public $id = 0;
    public $name = '';
    public $categories = array ();
    public $child;
}

<?php

namespace DeicerTest\Model;

use \Deicer\Model\AbstractModel;
use \Deicer\Model\ModelInterface;

/**
 * Deicer Test Model
 *
 * Represents a generic concrete implementation of a Deicer Model
 *
 * @category   DeicerTest
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
    protected $foo;
    private $bar;
}

<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Model;

use Iterator;
use Countable;
use ArrayAccess;
use Deicer\Model\ComponentInterface;
use Deicer\Stdlib\ClearableInterface;
use Deicer\Stdlib\ExtractableInterface;

/**
 * Deicer Abstract Model Composite
 *
 * Represents a composite set of domain specific models.
 *
 * @category   Deicer
 * @package    Model
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ModelCompositeInterface extends
 ComponentInterface,
 ExtractableInterface,
 ClearableInterface,
 Iterator,
 Countable,
 ArrayAccess
{
}

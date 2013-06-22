<?php

namespace Colt\Exception\Type;

/**
 * Colt Non String Type Exception
 *
 * Implies that a type strength constraint expecting a string been violated.
 *
 * @category   Colt
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonStringException extends \LogicException implements ExceptionInterface
{
    /**
     * Default exception code
     * 
     * @const string
     */
    const CODE = 1003;

    /**
     * Default exception message
     * 
     * @const string
     */
    const MESSAGE = 'Unexpected non-string type';
    
    /**
     * Type exception constructor
     *
     * {@inheritdoc} 
     */
    public function __construct($message = self::MESSAGE, $code = self::CODE, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

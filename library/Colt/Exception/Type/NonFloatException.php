<?php

namespace Colt\Exception\Type;

/**
 * Colt Non-Float Type Exception
 *
 * Implies that a type strength constraint expecting a float been violated.
 *
 * @category   Colt
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonFloatException extends \LogicException implements ExceptionInterface
{
    /**
     * Default exception code
     * 
     * @const string
     */
    const CODE = 1002;

    /**
     * Default exception message
     * 
     * @const string
     */
    const MESSAGE = 'Unexpected non-float type';
    
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

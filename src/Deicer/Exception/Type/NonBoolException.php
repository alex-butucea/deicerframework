<?php
/**
 * Deicer Framework (http://github.com/alex-butucea/deicerframework)
 *
 * @link       http://github.com/alex-butucea/deicerframework for canonical source repository
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */

namespace Deicer\Exception\Type;

/**
 * Deicer Non-Bool Type Exception
 *
 * Implies that a type strength constraint expecting a boolean been violated.
 *
 * @category   Deicer
 * @package    Exception
 * @subpackage Type
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
class NonBoolException extends \LogicException implements ExceptionInterface
{
    /**
     * Default exception code
     * 
     * @const string
     */
    const CODE = 1000;

    /**
     * Default exception message
     * 
     * @const string
     */
    const MESSAGE = 'Unexpected non-bool type';
    
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
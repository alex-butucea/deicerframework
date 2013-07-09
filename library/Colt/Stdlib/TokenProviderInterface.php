<?php

namespace Colt\Stdlib;

/**
 * Interface for objects that provide a uniquely identifiable token
 * 
 * @category   Colt
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com> 
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface TokenProviderInterface
{
    /**
     * Get unique token
     *
     * @return string
     */
    public function getToken();
}

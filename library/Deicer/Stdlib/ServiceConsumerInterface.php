<?php

namespace Deicer\Stdlib;

/**
 * Interface for objects that consume a given service
 *
 * @category   Deicer
 * @package    Stdlib
 * @version    $id$
 * @copyright  2013 Alex Butucea <alex826@gmail.com>
 * @author     Alex Butucea <alex826@gmail.com>
 * @license    The MIT License (MIT) {@link http://opensource.org/licenses/MIT}
 */
interface ServiceConsumerInterface
{
    /**
     * Internalises the given service
     * 
     * @param  ServiceInterface $service The service to consume
     * @return void
     */
    public function setService(ServiceInterface $service);
}
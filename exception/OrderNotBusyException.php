<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 25.08.15
 * Time: 19:57
 */

namespace Exception;
use Arbor\Core\Exception;


/**
 * @package Exception
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class OrderNotBusyException extends Exception
{

    /**
     * OrderNotBusyException constructor.
     */
    public function __construct()
    {
        parent::__construct(1,'Order not busy.');
    }
}
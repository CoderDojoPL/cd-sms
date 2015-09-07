<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-08-26
 * Time: 21:09
 */

namespace Exception;


use Arbor\Core\Exception;

class RoleNotFoundException extends Exception
{

    /**
     * RoleNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(1, "Role not found");
    }
}
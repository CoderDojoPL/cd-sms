<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-12
 * Time: 17:01
 */

namespace Exception;


use Arbor\Core\Exception;

class UserNotFoundException extends Exception
{

    /**
     * UserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(1, "User not found");
    }
}
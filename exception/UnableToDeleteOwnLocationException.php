<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-24
 * Time: 20:02
 */

namespace Exception;


use Arbor\Core\Exception;

class UnableToDeleteOwnLocationException extends Exception
{

    /**
     * UnableToDeleteOwnLocationException constructor.
     */
    public function __construct()
    {
        parent::__construct(1, 'Unable to delete own location');
    }
}
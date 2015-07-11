<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-11
 * Time: 16:50
 */

namespace Exception;


use Arbor\Core\Exception;

class LocationNotFoundException extends Exception
{

    /**
     * LocationNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(1, "Location not found");
    }
}
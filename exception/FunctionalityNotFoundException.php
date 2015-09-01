<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-08-25
 * Time: 21:46
 */

namespace Exception;


use Arbor\Core\Exception;

/**
 * @package Exception
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class FunctionalityNotFoundException extends Exception
{

    /**
     * FunctionalityNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(1, "Functionality not found");
    }
}
<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exception;


use Arbor\Core\Exception;

/**
 * @package Exception
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
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
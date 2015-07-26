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
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class LogRelationEntityNotFoundException extends Exception
{

    /**
     * LogRelationEntityNotFoundException constructor.
     *
     * @param string $name entity name
     */
    public function __construct($name)
    {
        parent::__construct(1, "Log relation entity ".$name." not found");
    }
}
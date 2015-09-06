<?php
/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

/**
 * Class FreeColumnFormatter
 *
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class FreeColumnFormatter extends ActionColumnFormatter
{

    public function __construct($prefix)
    {
        parent::__construct($prefix, array('free'));
    }

    public function render($data)
    {

        $type=$data[1];
        if ($type==2) {
            $this->buttons[]='free';
        } else {
            if (($key = array_search('free', $this->buttons)) !== false) {
                unset($this->buttons[$key]);
            }
        }
        return parent::render($data);

    }
}
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

    /**
     * Construct
     *
     * @param string $prefix url prefix
     */
    public function __construct($prefix,$buttons=array())
    {
        parent::__construct($prefix,$buttons);
    }

    /**
     * {@inheritdoc}
     */
    public function render($data)
    {
        $html='';
        $type=$data[1];
        if ($type==2) {
            $html.=$this->renderButton('free','Free',$data);
        }

        foreach($this->buttons as $button){
            $html.=$this->renderButton($button['action'],$button['label'],$data);
        }


        return $html;
    }
}
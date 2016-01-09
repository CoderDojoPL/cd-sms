<?php
/**
 * Created by PhpStorm.
 * User: DrafFter
 * Date: 2015-07-24
 * Time: 20:15
 */

namespace Common;


class LocationIndexColumnFormatter extends ActionColumnFormatter
{
    /**
     * @var
     */
    private $user;
    private $origButtons;

    public function __construct($prefix, $buttons, $user)
    {
        parent::__construct($prefix, $buttons);
        $this->user = $user;
        $this->origButtons = $buttons;
    }

    public function render($data)
    {

        $html='';

        foreach($this->buttons as $button){
            $html.=$this->renderButton($button['action'],$button['label'],$data);
        }

        if ($data[0] != $this->user->getLocation()->getId()) {
            $html.=$this->renderButton('remove','Remove',$data);
        }

        return $html;
    }
}
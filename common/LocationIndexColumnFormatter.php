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
        if ($data[0] == $this->user->getLocation()->getId()) {
            if (($key = array_search('remove', $this->buttons)) !== false) {
                unset($this->buttons[$key]);
            }
        } else {
            $this->buttons = $this->origButtons;
        }
        return parent::render($data);

    }
}
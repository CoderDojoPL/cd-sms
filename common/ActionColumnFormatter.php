<?php

namespace Common;

use Arbor\Component\Grid\ColumnFormatter;

class ActionColumnFormatter implements ColumnFormatter{

	private $prefix;
	private $buttons;
	public function __construct($prefix,$buttons){
		$this->prefix=$prefix;
		$this->buttons=$buttons;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($data){

		$html='';
		$action='';
		$icon='';
		foreach($this->buttons as $button){
			$action=$button;
			$href='href=""';
			switch($button){
				case 'show':
					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
				break;
				case 'edit':
					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
				break;
				case 'remove':
					$href='href="/'.$this->prefix.'/'.$action.'/'.$data.'"';
				break;
			}

			$html.='<a type="button" '.$href.' class="btn btn-default btn-xs">'.ucfirst($action).'</a>';

		}

		return $html;

	}

}
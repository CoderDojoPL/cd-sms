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

use Arbor\Component\Grid\ColumnFormatter;

/**
 * Formatter for grid column with action buttons
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class ActionColumnFormatter implements ColumnFormatter{

	private $prefix;
	protected $buttons=array();

	/**
	 * @param string $prefix - name to generate link urls e.g. order, device
	 * @param array $buttons - list of buttons to display
	 */
	public function __construct($prefix,$buttons){
		$this->prefix=$prefix;
		foreach($buttons as $button){
			$this->addButton($button,ucfirst($button));
		}
	}

	/**
	 * Add button to column
	 *
	 * @param strng $action
	 * @param string $label
	 */
	public function addButton($action,$label){
		$this->buttons[]=array('action'=>$action,'label'=>$label);
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		$html='';
		foreach($this->buttons as $button){
			$html.=$this->renderButton($button['action'],$button['label'],$data);
		}
		return $html;
	}

	/**
	 * Generate html for button
	 *
	 * @param string $action
	 * @param string $label
	 * @param array $data
	 * @return string html code
	 */
	protected function renderButton($action,$label,$data){
		$href='href="/'.$this->prefix.'/'.$action.'/'.$data[0].'"';

		return '<a type="button" '.$href.' class="btn btn-default btn-xs">'.$label.'</a>';

	}

}
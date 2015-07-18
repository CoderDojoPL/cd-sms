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
	private $buttons;

	/**
	 * @param string $prefix - name to generate link urls e.g. order, device
	 * @param array $buttons - list of buttons to display
	 */
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
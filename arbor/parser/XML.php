<?php

/**
 * ArborPHP: Freamwork PHP (http://arborphp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://arborphp.com ArborPHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Arbor\Parser;

/**
 * Xml file parser.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class XML{
	
	public function __construct($data,$type='FILE'){
		if($type=='FILE')
			$this->data=new \SimpleXMLElement(file_get_contents($data));
		else
			$this->data=new \SimpleXMLElement($data);
	}

	public function get(){
		return $this->data;
	}

	public function getChild($name){
		$data=$this->data->children();
		return $data[0];//TODO dorobić wykrywanie "obiekt nie istnieje"
	}
}
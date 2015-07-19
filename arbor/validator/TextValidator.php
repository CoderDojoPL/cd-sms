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

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for text
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.18.0
 */
class TextValidator extends Validator{
	
	/**
	 * {@inheritdoc}
	 */
	public function validate($value){

		$empty=false;

		try{
			$empty=$this->getOption('empty');
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		if(!$value && !$empty){
			return 'Value can not empty';
		}

		try{
			$pattern=$this->getOption('pattern');
			if(!preg_match('/'.$pattern.'/',$value)){
				return 'Invalid pattern format.';
			}

		}
		catch(ValueNotFoundException $e){
			//ignore
		}

	}
}

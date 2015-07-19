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
 * Validator for file
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.18.0
 */
class FileValidator extends Validator{
	
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

		if(!$value && $empty){
			return;
		}

		if(!$value){
			return 'File not uploaded.';
		}

		if($value->isError()){
			return $value->getError();
		}


		try{
			$accept=$this->getOption('accept');
			if(!preg_match('/'.str_replace(array('*','/'),array('.+','\\/'),$accept).'/' ,$value->getExtension())){
				return 'Invalid file type.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		try{
			$maxSize=$this->getOption('maxSize');
			if($value->getSize()>$maxSize){
				return 'File is too large.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}
	}
}

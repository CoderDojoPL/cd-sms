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

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Core\ValidatorService;

/**
 * Service to validate
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.13.0
 */
class Validator implements ValidatorService{
	
	private $entityManager;

	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($validator,$value){	
		return $validator->validate($value);
	}

	/**
	 * validate multiple values
	 * @arg validators - array with value and validators: 
	 * array(
	 * 'field name'=>array(
	 *		'validator rule class'
	 *		,'value'
	 *	)
	 *	,'another field name'=>array(
	 *		'validator rule class'
	 *		,'value')
	 *	)
	 * @return array with errors. If success then empty array.
	 * @since 0.13.0
	 */
	public function multiValidate($validators){
		$errors=array();
		foreach($validators as $kValidate=>$validate){
			$error=$this->validate($validate[0],$validate[1]);
			if($error)
				$errors[]=array('field'=>$kValidate,'message'=>$error);
		}

		return $errors;
	}

	/**
	 * Validate data from storage array
	 * @arg $validators - array with rules validation example: array('nameField'=>'Validator\ExampleClassValidator')
	 * @arg $storage - array with values, example array('nameField1'=>'value1','nameField2'=>'value2')
	 * @return array with errors. If success then empty array.
	 * @since 0.13.0
	 */
	public function storageValidate($validators,$storage){
		$errors=array();
		foreach($validators as $kValidate=>$validate){
			if(!isset($storage[$kValidate])){
				$errors[]=array('field'=>$kValidate,'message'=>'Value '.$kValidate.' not found.');
				continue;
			}

			$error=$this->validate($validate,$storage[$kValidate]);
			if($error)
				$errors[]=array('field'=>$kValidate,'message'=>$error);
		}

		return $errors;

	}
}
<?php

namespace Arbor\Service;

use Arbor\Contener\ServiceConfig;
use Arbor\Core\ValidatorService;

class Validator implements ValidatorService{
	
	private $entityManager;

	public function __construct(ServiceConfig $serviceConfig){		
	}

	/**
	 * validate once value
	 * @arg validator - class validator rule
	 * @arg value - valut to validation
	 * @return null if success or message error if fail
	 * @since 0.13.0
	 */
	public function validate($validator,$value){
		$validObject=new $validator();
		return $validObject->validate($value);
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
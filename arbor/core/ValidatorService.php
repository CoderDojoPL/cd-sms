<?php

namespace Arbor\Core;

interface ValidatorService{

	/**
	 * validate once value
	 * @arg validator - class validator rule
	 * @arg value - valut to validation
	 * @return null if success or message error if fail
	 * @since 0.13.0
	 */
	public function validate($validator,$value);

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
	public function multiValidate($validators);

	/**
	 * Validate data from storage array
	 * @arg $validators - array with rules validation example: array('nameField'=>'Validator\ExampleClassValidator')
	 * @arg $storage - array with values, example array('nameField1'=>'value1','nameField2'=>'value2')
	 * @return array with errors. If success then empty array.
	 * @since 0.13.0
	 */
	public function storageValidate($validators,$storage);

}


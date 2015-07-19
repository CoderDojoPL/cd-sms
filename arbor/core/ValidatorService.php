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

namespace Arbor\Core;

/**
 * Interface for validtor service.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.13.0
 */
interface ValidatorService{

	/**
	 * validate once value
	 * @param Arbor\Core\Validator $validator - class validator rule
	 * @param mixed $value - valut to validation
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


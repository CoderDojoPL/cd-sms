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

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;
use Arbor\Validator\TextValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.15.0
 */
class TextField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='text';

		if(!isset($options['validator'])){
			$this->setValidator(new TextValidator());
		}

		parent::__construct($options);
	}

}
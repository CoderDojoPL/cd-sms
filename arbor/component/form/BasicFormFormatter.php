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

/**
 * Formatter for FormBuilder
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.18.0
 */
class BasicFormFormatter implements FormFormatter{

    /**
     * {@inheritdoc}
     */
	public function renderField(FormField $field){
		$html=$field->render();
		return $html;
	}

    /**
     * {@inheritdoc}
     */
	public function renderFormBegin($tags){
		$template='<FORM ';
		foreach($tags as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.$tag.'" ';
		}

		$template.=' >';

		return $template;
	}

    /**
     * {@inheritdoc}
     */
	public function renderFormEnd(){
		return '</FORM>';
	}

    /**
     * {@inheritdoc}
     */
	public function renderSubmit($tags){
		$template='<BUTTON ';
		$value=$tags['value'];
		unset($tags['value']);

		foreach($tags as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.$tag.'" ';
		}

		$template.=' >'.$value.'</BUTTON>';

		return $template;

	}
}
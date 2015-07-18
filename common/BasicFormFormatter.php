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

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\FormField;


/**
 * Formatter for form.
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class BasicFormFormatter implements FormFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function renderField(FormField $field){

		$field->addClass('form-control');
		$tags=$field->getTags();
		$groupClass='form-group';
		if(isset($tags['disabled']) && $tags['disabled']){
			$groupClass.=' hide';
		}
		if(!$field->isValid()){
			$groupClass.=' has-error';
		}

		if($field->isRequired()){
			$groupClass.=' has-required';
		}

		$html='<div class="'.$groupClass.'">
			<label class="col-sm-3 control-label" for="'.$field->getId().'">'.$field->getLabel().'</label>
			<div class="col-sm-6">
			'.$field->componentRender().'
			'.(!$field->isValid()?'<label for="name" class="error">'.$field->getError().'</label>':'').'
			</div>
		</div>';

		return $html;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderFormBegin($tags){
		if(!isset($tags['class'])){
			$tags['class']='';
		}

		$tags['class'].=' form-horizontal form-border';
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
		if(!isset($tags['class'])){
			$tags['class']='';
		}

		$tags['class'].=' btn btn-primary';

		$button='<BUTTON ';
		foreach($tags as $kTag=>$tag){
			if($tag!='')
				$button.=$kTag.'="'.$tag.'" ';
		}

		$button.='>Apply</BUTTON>';

		return '<div class="form-group">
					<div class="col-sm-offset-8 col-sm-1">
						'.$button.'
					</div>
				</div>';
	}
}
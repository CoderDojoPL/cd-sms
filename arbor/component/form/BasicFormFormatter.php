<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;

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
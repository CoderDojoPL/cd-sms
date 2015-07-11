<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
class TextareaField extends FormField{

	private $data='';

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		if(!isset($options['validator'])){
			$options['validator']='Arbor\Validator\Text'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		if(isset($options['value'])){
			$this->data=$options['value'];
			unset($options['value']);
		}

		parent::__construct($options);
	}

    /**
     * {@inheritdoc}
     */
	public function render(){
		$template=$this->labelRender();
		$template.=$this->componentRender();
		return $template;
	}

    /**
     * {@inheritdoc}
     */
	public function labelRender(){
		return '<label for="'.$this->getId().'">'.$this->getLabel().'</label>';
	}

    /**
     * {@inheritdoc}
     */
	public function setData($value){
		$this->data=$value;
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		return $this->data;
	}

    /**
     * {@inheritdoc}
     */
	public function clearData(){
		return $this->data=null;
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<textarea ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!='')
				$template.=$kTag.'="'.$tag.'" ';
		}

		$template.='>'.$this->getData().'</textarea>';

		return $template;
	}

}
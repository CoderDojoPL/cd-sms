<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
class SelectField extends FormField{
	private $collection=array();
	private $data=null;

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		if(isset($options['collection'])){
			$this->collection=$options['collection'];
			unset($options['collection']);
		}

		if(!isset($options['validator'])){
			$options['validator']='Arbor\Validator\Text'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}


		parent::__construct($options);
	}

	/**
	 * Get collection data (options value)
	 * @return array - eg:
	 * array(
	 * 	array(
	 * 		'value'=>'{string}'
	 * 		,'label'=>'{string}'
	 * 	)
	 * 	,...
	 * )
	 * @since 0.15.0
	 */
	public function getCollection(){
		return $this->collection;
	}

	/**
	 * Set collection data (options value)
	 * @param array $collection - eg:
	 * array(
	 * 	array(
	 * 		'value'=>'{string}'
	 * 		,'label'=>'{string}'
	 * 	)
	 * 	,...
	 * )
	 * @since 0.15.0
	 */
	public function setCollection($collection){
		$this->collection=$collection;
	}

	/**
	 * set html tag multiple
	 * @param boolean $flag - value of tag multiple
	 * @since 0.15.0
	 */
	public function setMultiple($flag){
		$this->setTag('multiple',$flag);
	}

	/**
	 * get html tag multiple
	 * @return boolean
	 * @since 0.15.0
	 */
	public function isMultiple(){
		$tags=$this->getTags();
		return (isset($tags['multiple']) && $tags['multiple']);
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
		$template='<select ';
		foreach($this->getTags() as $kTag=>$tag){
			if($tag!=''){
				if($kTag=='name' && $this->isMultiple()){
					$tag.='[]';
				}

				$template.=$kTag.'="'.$tag.'" ';

			}
		}

		$template.='>';
		$values=(is_array($this->getData())?$this->getData():array($this->getData()));
		foreach($this->collection as $option){
			$template.='<option value="'.$option['value'].'" '.(in_array($option['value'], $values)?'selected':'').'>'.$option['label'].'</option>';
		}

		$template.='</select>';
		return $template;
	}
}

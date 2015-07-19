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
			$this->setValidator(new TextValidator());
		}


		parent::__construct($options);
	}

	/**
	 * Get collection data (options value)
	 *
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
	 *
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
	 * Set html tag multiple
	 *
	 * @param boolean $flag - value of tag multiple
	 * @since 0.15.0
	 */
	public function setMultiple($flag){
		$this->setTag('multiple',$flag);
	}

	/**
	 * Get html tag multiple
	 *
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
		return '<label for="'.$this->getId().'">'.htmlspecialchars($this->getLabel()).'</label>';
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

				$template.=$kTag.'="'.htmlspecialchars($tag).'" ';

			}
		}

		$template.='>';
		$values=(is_array($this->getData())?$this->getData():array($this->getData()));
		foreach($this->collection as $option){
			$template.='<option value="'.htmlspecialchars($option['value']).'" '.(in_array($option['value'], $values)?'selected':'').'>'.htmlspecialchars($option['label']).'</option>';
		}

		$template.='</select>';
		return $template;
	}
}

<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Provider\Request;
use Arbor\Core\ValidatorService;

/**
 * @since 0.15.0
 */
class FileField extends InputField{

	private $data;
	/**
	 * {@inheritdoc}
	 */
	public function __construct($options){

		$options+=array(
			'multiple'=>false
		);

		$options['type']='file';

		if(!isset($options['validator'])){
			$options['validator']='Arbor\Validator\Text'.(!isset($options['required']) || !$options['required']?'OrEmpty':'');
		}

		parent::__construct($options);
	}

	/**
	 * set html tag multiple
	 * @param boolean $flag - value of tag multiple
	 * @since 0.17.0
	 */
	public function setMultiple($flag){
		$this->setTag('multiple',$flag);
	}

	/**
	 * get html tag multiple
	 * @return boolean
	 * @since 0.17.0
	 */
	public function isMultiple(){
		$tags=$this->getTags();
		return (isset($tags['multiple']) && $tags['multiple']);
	}

	/**
	 * set html tag accept
	 * @param string $accept - value of tag accept
	 * @since 0.18.0
	 */
	public function setAccept($accept){
		$this->setTag('accept',$accept);
	}

	/**
	 * get html tag accept
	 * @return string $accept
	 * @since 0.18.0
	 */
	public function getAccept(){
		return $this->accept;
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
		$this->data=null;
	}

	public function componentRender(){
		$template='<input ';

		foreach($this->getTags() as $kTag=>$tag){
			if($tag!=''){
				if($kTag=='name' && $this->isMultiple()){
					$tag.='[]';
				}				
				$template.=$kTag.'="'.$tag.'" ';
			}
		}
		$template.=' />';
		return $template;

	}

}

<?php

namespace Arbor\Component\Form;

use Arbor\Component\Form\FormFormatter;
use Arbor\Component\Form\BasicFormFormatter;
use Arbor\Component\Form\Designer;
use Arbor\Core\RequestProvider;
use Arbor\Core\ValidatorService;
use Arbor\Exception\FieldNotFoundException;
use Arbor\Exception\FileNotUploadedException;
use Arbor\Core\FileUploaded;

/**
 * @since 0.13.0
 */
class FormBuilder{
	private $formatter;
	private $fields=array();
	private $formTags=array();
	private $submitTags=array();
	private $validatorService;
	private $isConfirmed=false;

	public function __construct(){
		$this->formatter=new BasicFormFormatter();
		$this->formTags=array(
			'method'=>'post'
			,'id'=>null
			,'class'=>null
			,'enctype'=>null
			);

		$this->submitTags=array(
			'value'=>'Apply'
			,'id'=>null
			,'class'=>null);
	}

	/**
	 * Set service to validate field data
	 * $arg validatorService
	 * @since 0.13.0
	 */
	public function setValidatorService(ValidatorService $validatorService){
		$this->validatorService=$validatorService;
	}

	/**
	 * Set formatter with html rule pattern
	 * @arg formatter
	 * @since 0.13.0
	 */
	public function setFormatter(FormFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set designer with rule to generate fields
	 * @param Arbor\Component\Form\Designer $designer
	 * @since 0.18.0
	 */
	public function setDesigner(Designer $designer){
		$this->designer=$designer;
		$this->designer->build($this);
	}

	/**
	 * Set addon form tags
	 * @arg tags - array widh data (all field is optional):
	 * array(
	 *	'method'=>'post' //"post" or "get"
	 *	,'id'=>'id1' //html tag id
	 *	,'class'=>'class1' //html tag class
	 *	,'enctype'=>'multipart/form-data' //html tag enctype eg. "text/plain", "multipart/form-data" or "application/x-www-form-urlencoded" 
	 *	)
	 * @since 0.13.0
	 */
	public function setFormTags($tags){
		$this->formTags=array_merge($this->formTags,$tags);
	}

	/**
	 * Set submit button tags
	 * @arg tags - arrawy with data:
	 * array(
	 * 	'value'=> 'Apply' //label button, default: Apply
	 * 	,'id'=>'id1' //html tag id
	 * 	,'class'=>'class1' //html tag class
	 * )
	 * @since 0.13.0
	 */
	public function setSubmitTags($tags){
		$this->submitTags=array_merge($this->submitTags,$tags);
	}

	/**
	 * Add form field
	 * @arg field - object of FormField
	 * @since 0.15.0
	 */
	public function addField(FormField $field){

		$this->fields[]=$field;

		if($field->getName()==null){
			$field->setName('name_'.count($this->fields));
		}

		if($field->getId()==null){
			$field->setId('id_'.count($this->fields));
		}

		if($field instanceof FileField){
			$this->formTags['enctype']='multipart/form-data';
		}

	}

	/**
	 * Remove field from generator
	 * @param string $name field name
	 * @since 0.18.0
	 */
	public function removeField($name){
		for($i=0; $i<count($this->fields); $i++){
			if($this->fields[$i]->getName()==$name){
				array_splice($this->fields,$i,1);
				break;
			}
		}
	}

	/**
	 * Generate html form string
	 * @return string with html form
	 * @since 0.13.0
	 */
	public function render(){
		$html=$this->formatter->renderFormBegin($this->formTags);
		foreach($this->fields as $field){
			$html.=$this->formatter->renderField($field);
		}

		$html.=$this->renderSubmit();
		$html.=$this->renderEnd();
		return $html;
	}

	/**
	 * Generate html string for selected field
	 * @param string $name field name
	 * @return string with html field
	 * @since 0.16.0
	 */
	public function renderField($name){
		$html='';

		$field=$this->getField($name);
		$html.=$this->formatter->renderField($field);

		return $html;
	}

	/**
	 * Generate html string for open form tag
	 * @return string with html open form tag
	 * @since 0.16.0
	 */
	public function renderBegin(){
		return $this->formatter->renderFormBegin($this->formTags);
	}

	/**
	 * Generate html string for close form tag
	 * @return string with html close form tag
	 * @since 0.16.0
	 */
	public function renderEnd(){
		return $this->formatter->renderFormEnd();
	}

	/**
	 * Generate html string for open form submit
	 * @return string with html open form submit
	 * @since 0.16.0
	 */
	public function renderSubmit(){
		return $this->formatter->renderSubmit($this->submitTags);
	}

	public function __toString(){
		return $this->render();
	}

	/**
	 * get field object
	 * @param $name - field name (html name tag)
	 * @return FormField
	 * @throws Arbor\Exception\FieldNotFoundException - invalid param name
	 * @since 0.13.0
	 */
	public function getField($name){
		foreach($this->fields as $field){
			if($field->getName()==$name)
				return $field;
		}

		throw new FieldNotFoundException($name);
	}

	/**
	 * check confirmed form (clicked submit button in frontend/sended fields value)
	 * @return if success then true else false
	 * @since 0.13.0
	 */
	public function isConfirmed(){
		return $this->isConfirmed;
	}

	/**
	 * check valid form
	 * @return if success then true else false
	 * @since 0.13.0
	 */
	public function isValid(){
		if(!$this->isConfirmed())
			return false;

		$errors=$this->getErrors();
		
		return count($errors)==0;

	}

	/**
	 * set default values for fiels
	 * @param array eg:
	 * array(
	 * '{text field name 1}'=>'{text value name 1}'
	 * ,'{text field name 2}'=>'{text value name 2}'
	 * )
	 * @since 0.13.0
	 */
	public function setData($data){//FIXME aktualnie muszą być dodane pola, aby ustawił wartości. Trzeba by zmienić by zachowywał dane, a potem je ustawiał podczas renderowania lub walidowania
		foreach($this->fields as $field){
			if(isset($data[$field->getName()])){
				$field->setData($data[$field->getName()]);
			}
		}
	}

	/**
	 * get data from fields
	 * @return array
	 * @since 0.17.0
	 */
	public function getData(){
		$data=array();
		foreach($this->fields as $field){
			if(preg_match('/^(.*?)\[(.*)\]$/',$field->getName(),$result)){
				if($result[2]==''){
					//FIXME autoincrement field
				}
				else{
					if(!isset($data[$result[1]])){
						$data[$result[1]]=array();
					}

					$data[$result[1]][$result[2]]=$field->getData();
				}
			}
			else{
				$data[$field->getName()]=$field->getData();
			}
		}

		return $data;
	}

	/**
	 * remove all field data
	 * @since 0.17.0
	 */
	public function clearData(){
		$data=array();
		foreach($this->fields as $field){
			$field->clearData();
		}
	}

	/**
	 * submit form. Check http confirm and validate fields
	 * @param Arbor\Core\RequestProvider $request
	 * @since 0.17.0
	 */
	public function submit(RequestProvider $request){
		$this->isConfirmed=false;

		if($this->formTags['method']=='post' && $request->getType()=='POST'){
			$this->isConfirmed=true;
		}

		$query=$request->getQuery();
		if(count($this->fields)>0 && $this->formTags['method']=='get' && isset($query[$this->fields[0]->getName()])){
			$this->isConfirmed=true;
		}

		if(!$this->isConfirmed)
			return;

		$storage=array();
		if($this->formTags['method']=='post'){
			$storage=$request->getData();
		}
		else{
			$storage=$request->getQuery();			
		}

		//set field data

		$result=array();
		foreach($this->fields as $field){

			if(isset($storage[$field->getName()])){
				$field->setData($storage[$field->getName()]);
			}
			else if($field instanceof FileField){
				try{
					$field->setData($request->getFile($field->getName()));
				}
				catch(FileNotUploadedException $e){
					$field->setData('');
				}
			}
			else if(preg_match('/^(.*?)\[(.*)\]$/',$field->getName(),$result)){
				if($result[2]==''){
					//FIXME autoincrement field
				}
				else{
					$field->setData($storage[$result[1]][$result[2]]);
				}
			}
		}

		//validate
		if($request->isFullUploadedData()){
			foreach($this->fields as $field){
				if($field->getValidator()){
					if($error=$this->validatorService->validate($field->getValidator(),$field->getData())){
						$field->setError($error);
					}

				}
			}
		}
		else{
			foreach($this->fields as $field){
				$field->setError('Request data is too large.');
			}
		}
	}

	/**
	 * validate fields and get errors
	 * @arg request
	 * @return array with errors if success then empty array
	 * @since 0.13.0
	 */
	public function getErrors(){
		$errors=array();
		foreach($this->fields as $field){
			if(!$field->isValid()){				
				$errors[]=array('field'=>$field->getLabel(),'message'=>$field->getError());
			}
		}

		return $errors;
	}
}

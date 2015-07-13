<?php

namespace Arbor\Validator;
use Arbor\Core\Validator;
use Arbor\Exception\ValueNotFoundException;

/**
 * Validator for file
 * @since 0.18.0
 */
class FileValidator extends Validator{
	
	/**
	 * {@inheritdoc}
	 */
	public function validate($value){

		$empty=false;

		try{
			$empty=$this->getOption('empty');
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		if(!$value && $empty){
			return;
		}

		if(!$value){
			return 'File not uploaded.';
		}

		if($value->isError()){
			return $value->getError();
		}


		try{
			$accept=$this->getOption('accept');
			if(!preg_match('/'.str_replace(array('*','/'),array('.+','\\/'),$accept).'/' ,$value->getExtension())){
				return 'Invalid file type.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		try{
			$maxSize=$this->getOption('maxSize');
			if($value->getSize()>$maxSize){
				return 'File is too large.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}
	}
}

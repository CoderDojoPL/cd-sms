<?php

/*
 * This file is part of the ArborPHP.
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Library\Doctrine\Form;

use Arbor\Component\Form\Designer;
use Arbor\Component\Form\FormBuilder;
use Arbor\Component\Form\TextField;
use Arbor\Component\Form\TextareaField;
use Arbor\Component\Form\NumberField;
use Arbor\Component\Form\SelectField;
use Arbor\Component\Form\CheckboxField;
use Arbor\Component\Form\DateField;

use Doctrine\ORM\Mapping\ClassMetadata;

use Library\Doctrine\Exception\DoctrineTypeNotSupportedException;

/**
 * Map entity to form.
 *
 * Class DoctrineDesigner
 * @package Library\Doctrine\Form
 */
class DoctrineDesigner implements Designer{

	private $entityName;
	private $doctrineService;
	
	/**
	 * @param \Library\Doctrine\Service\Doctrine $doctrineService
	 * @param string $entityName
	 * @param array $filter
	 * @since 0.18.0
	 */
	public function __construct($doctrineService,$entityName,$filter=null){
		$this->entityName=$entityName;
		$this->doctrineService=$doctrineService;
		$this->filter=$filter;
	}

    /**
     * {@inheritdoc}
     */
	public function build(FormBuilder $form){
		$metaData=$this->doctrineService->getEntityManager()->getClassMetadata($this->entityName);

		foreach($metaData->getFieldNames() as $fieldName){
			if(!$metaData->isIdentifier($fieldName) && (!$this->filter || in_array($fieldName, $this->filter))){
				$this->createField($form,$metaData,$fieldName);
			}
		}

		foreach($metaData->getAssociationNames() as $fieldName){
			if(!$metaData->isIdentifier($fieldName) && (!$this->filter || in_array($fieldName, $this->filter))){
				$this->createAssociationField($form,$metaData,$fieldName);
			}
		}

		
	}

	/**
	 * @param FormBuilder $form
	 * @param ClassMetadata $metaData
	 * @param string $fieldName
	 * @throws DoctrineTypeNotSupportedException
	 */
	public function createField($form,$metaData,$fieldName){
		$type=$metaData->getTypeOfColumn($fieldName);
		switch($type){
			case 'string':
				$formField=$this->createFieldString($metaData,$fieldName);
			break;
			case 'text':
				$formField=$this->createFieldText($metaData,$fieldName);
			break;
			case 'integer':
				$formField=$this->createFieldInteger($metaData,$fieldName);
			break;
			case 'boolean':
				$formField=$this->createFieldBoolean($metaData,$fieldName);
			break;
			case 'datetime':
				$formField=$this->createFieldDateTime($metaData,$fieldName);
			break;
			case 'decimal':
				$formField=$this->createFieldDecimal($metaData,$fieldName);
			break;
			default:
				throw new DoctrineTypeNotSupportedException($type);
		}

		$formField->setLabel($this->translateName($fieldName));
		$formField->setName($fieldName);

		if(!$metaData->isNullable($fieldName)){
			$formField->setRequired(true);
		}


		$form->addField($formField);

	}

	/**
	 * @param FormBuilder $form
	 * @param ClassMetadata $metaData
	 * @param string $fieldName
	 */
	public function createAssociationField($form,$metaData,$fieldName){
		$formField=new SelectField(array());
		$formField->setLabel($this->translateName($fieldName));
		$formField->setName($fieldName);
		$mapping=$metaData->getAssociationMapping($fieldName);
		$required=false;


		$targetEntityName=$metaData->getAssociationTargetClass($fieldName);
		$targetEntity=$this->doctrineService->getRepository($targetEntityName);

		if($metaData->isCollectionValuedAssociation($fieldName)){
			$formField->setMultiple(true);
			$formField->setRequired(false);
		}
		else{
			foreach($mapping['joinColumns'] as $joinColumn){
				if(!$joinColumn['nullable']){
					$required=true;
					break;
				}
			}
			$formField->setRequired($required);

		}

		$collection=static::entityToCollection($targetEntity->findAll(),!$formField->isMultiple());
		$formField->setCollection($collection);


		$form->addField($formField);

	}

	public static function entityToCollection($entity,$appendEmptyRecord){
		$values=array();
		if($appendEmptyRecord){
			$values[]=array('value'=>'','label'=>'Select...');			
		}

		foreach($entity as $record){
			$values[]=array('value'=>$record->getId(),'label'=>htmlspecialchars($record->__toString()));
		}

		return $values;
	}

	private function createFieldString($metaData,$fieldName){
		$formField=new TextField(array());
		return $formField;
	}

	private function createFieldDateTime($metaData,$fieldName){
		$formField=new DateField(array());
		return $formField;
	}

	private function createFieldText($metaData,$fieldName){
		$formField=new TextareaField(array());
		return $formField;
	}

	private function createFieldBoolean($metaData,$fieldName){
		$formField=new CheckboxField(array());
		return $formField;
	}

	private function createFieldDecimal($metaData,$fieldName){
		$formField=new NumberField(array('step'=>'any'));
		return $formField;
	}

	private function createFieldInteger($metaData,$fieldName){
		if($metaData->isAssociationWithSingleJoinColumn($fieldName)){//references

		}
		else{//normal integer
			$formField=new NumberField(array());
		}

		return $formField;
	}

	private function translateName($fieldName) {
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $parts = preg_split($re, $fieldName);
        return ucfirst(join($parts, " "));
}
}
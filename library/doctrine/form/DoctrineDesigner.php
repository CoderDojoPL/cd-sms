<?php
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

class DoctrineDesigner implements Designer{

	private $entityName;
	private $doctrineService;
	
	/**
	 * @param string $entityName
	 * @since 0.18.0
	 */
	public function __construct($doctrineService,$entityName){
		$this->entityName=$entityName;
		$this->doctrineService=$doctrineService;
	}

    /**
     * {@inheritdoc}
     */
	public function build(FormBuilder $form){
		$metaData=$this->doctrineService->getEntityManager()->getClassMetadata($this->entityName);

		foreach($metaData->getFieldNames() as $fieldName){
			if(!$metaData->isIdentifier($fieldName)){
				$this->createField($form,$metaData,$fieldName);
			}
		}

		foreach($metaData->getAssociationNames() as $fieldName){
			if(!$metaData->isIdentifier($fieldName)){
				$this->createAssociationField($form,$metaData,$fieldName);
			}
		}

		
	}

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

	public function createAssociationField($form,$metaData,$fieldName){
		$formField=new SelectField(array());
		$formField->setLabel($this->translateName($fieldName));
		$formField->setName($fieldName);

		$formField->setRequired(true);

		$targetEntityName=$metaData->getAssociationTargetClass($fieldName);
		$targetEntity=$this->doctrineService->getRepository($targetEntityName);

		if($metaData->isCollectionValuedAssociation($fieldName)){
			$formField->setMultiple(true);
		}

		$collection=$this->entityToCollection($targetEntity->findAll(),!$formField->isMultiple());
		$formField->setCollection($collection);


		$form->addField($formField);

	}

	private function entityToCollection($entity,$appendEmptyRecord){
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
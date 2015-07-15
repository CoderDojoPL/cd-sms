<?php

namespace Arbor\Test;

use Arbor\Test\BrowserEmulator;
use Arbor\Exception\InvalidQuerySelectorException;
use Arbor\Test\AnchorElement;
use Arbor\Test\FormElement;
use Arbor\Test\InputElement;
use Arbor\Test\TextareaElement;
use Arbor\Test\SelectElement;

class HTMLElement{
	protected $browser;
	protected $node;
	
	public function __construct(BrowserEmulator $browser,$node){
		$this->browser=$browser;
		$this->node=$node;
	}

	public function getId(){
		return $this->getAttribute('id');
	}

	public function getClass(){
		return $this->getAttribute('class');
	}

	public function getAttribute($name){
		return $this->node->getAttribute($name);
	}

	public function getElement($query){
		$nodes=$this->findElements($query);
		if(count($nodes)==0){
			throw new ElementNotFoundException();
		}

		return $nodes[0];
	}

	public function findElements($query){

		//detect parts
		if(!preg_match('/(?:^| )(.*?)((\.|#)(.*?)){0,1}($| )/',$query, $match)){
			throw new InvalidQuerySelectorException($query);
		}

		$tag=$match[1];

		$className=null;
		$id=null;
		if($match[2]=='.'){
			$className=$match[3];
		}

		if($match[2]=='#'){
			$id=$match[3];
		}
		
		$nodes=array();
		foreach($this->node->getElementsByTagName('*') as $node){

			if($tag && $tag!=$node->tagName){
				continue;
			}
			if($className && !in_array($className,explode(' ',$node->getAttribute('class')))){
				continue;
			}

			if($id && $id!=$node->getAttribute('id')){
				continue;
			}

			$htmlElement=null;
			switch($node->tagName){
				case 'a':
					$htmlElement=new AnchorElement($this->browser,$node);
				break;
				case 'form':
					$htmlElement=new FormElement($this->browser,$node);
				break;
				case 'input':
					$htmlElement=new InputElement($this->browser,$node);
				break;
				case 'textarea':
					$htmlElement=new TextareaElement($this->browser,$node);
				break;
				case 'select':
					$htmlElement=new SelectElement($this->browser,$node);
				break;
				default:
					$htmlElement=new HTMLElement($this->browser,$node);
				break;

			}
			$nodes[]=$htmlElement;

		}

		return $nodes;

	}

	public function getText(){
		return htmlspecialchars($this->getHtml());
	}

	public function getHtml() { 
		$innerHTML= '';
		$children = $this->node->childNodes; 
		foreach ($children as $child) { 
			$innerHTML.= $child->ownerDocument->saveXML( $child ); 
		} 

		return $innerHTML; 
	}
}
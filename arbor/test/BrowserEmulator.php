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

namespace Arbor\Test;

use Arbor\Exception\EmptyDataException;
use Arbor\Exception\InvalidQuerySelectorException;
use Arbor\Exception\ElementNotFoundException;
use Arbor\Test\Request;
use Arbor\Core\Enviorment;
use Arbor\Test\HTMLElement;
use Arbor\Provider\Session;
use Arbor\Test\AnchorElement;
use Arbor\Test\FormElement;

/**
 * Emulator for functionality test
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class BrowserEmulator{
	private $request;
	private $response;
	private $history=array();
	private $historyCursor=0;
	private $enviorment;

	public function __construct(Enviorment $enviorment,Session $session=null){
		$this->enviorment=$enviorment;
		if(!$session){
			$session=new Session($enviorment);
		}
		$this->session=$session;
	}

	public function loadPage($url){
		$this->executeRequest('GET',$url);
		$this->history[]=$url;
		$this->historyCursor++;
		return $this;
	}

	public function requestPost($url,$data=array()){
		$this->executeRequest('POST',$url,$data);
		$this->history[]=$url;
		$this->historyCursor++;

	}

	public function back(){
		$this->executeRequest('GET',$this->history[--$this->historyCursor]);
		return $this;
	}

	public function next(){
		$this->executeRequest('GET',$this->history[++$this->historyCursor]);
		return $this;
	}

	public function getUrl(){
		return $this->request->getUrl();
	}

	public function getResponse(){
		return $this->response;
	}

	public function getElement($query){
		$nodes=$this->findElements($query);
		if(count($nodes)==0){
			throw new ElementNotFoundException();
		}

		return $nodes[0];
	}

	public function findElements($query){
		if(!$this->response || $this->response->getContent()==''){
			throw new EmptyDataException();			
		}

		$document=new \DOMDocument();
		libxml_use_internal_errors(true);
		$document->loadHTML($this->response->getContent());
		libxml_clear_errors();

		//detect parts
		if(!preg_match('/(?:^| )(.*?)(?:(\.|#)(.*?)){0,1}($| )/',$query, $match)){
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
		foreach($document->getElementsByTagName('*') as $node){

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
					$htmlElement=new AnchorElement($this,$node);
				break;
				case 'form':
					$htmlElement=new FormElement($this,$node);
				break;
				case 'input':
					$htmlElement=new InputElement($this,$node);
				break;
				case 'textarea':
					$htmlElement=new TextareaElement($this,$node);
				break;
				case 'select':
					$htmlElement=new SelectElement($this,$node);
				break;
				default:
					$htmlElement=new HTMLElement($this,$node);
				break;

			}
			$nodes[]=$htmlElement;

		}

		return $nodes;

	}

	private function executeRequest($type,$url,$data=array()){
		$this->request=new Request($url,$this->enviorment,$this->session);
		$this->request->setType($type);
		$this->request->setData($data);
		$this->response=$this->request->execute();

		$this->detectRedirect();

	}

	private function detectRedirect(){
		if($this->response->getStatusCode()==302){
			$this->executeRequest('GET',$this->response->getHeader('Location'));
		}

	}

}
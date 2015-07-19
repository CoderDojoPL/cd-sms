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

namespace Arbor\Contener;

use Arbor\Parser\XML as XMLParser;
use Arbor\Exception\InvalidConfigKeyException;
use Arbor\Exception\ConfigAttributeNotFoundException;
use Arbor\Core\Enviorment;

/**
 * Contener with all config.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
class GlobalConfig{
	
	/**
	 * Path to configuration files.
	 *
	 * @var string $dir
	 */
	private $dir;

	/**
	 * Array with parsed configuration.
	 *
	 * @var array $data
	 */
	private $data;

	/**
	 * Enviorment.
	 *
	 * @var \Arbor\Core\Enviorment $dir
	 */
	private $enviorment;

	/**
	 * Constructor.
	 *
	 * @param string $dir path to config files
	 * @param \Arbor\Core\Enviorment $enviorment
	 * @since 0.1.0
	 */
	public function __construct($dir,Enviorment $enviorment){
		$this->dir=$dir;
		$this->enviorment=$enviorment;
		$this->data=array(
			'methods'=>array(),
			'services'=>array(),
			'events'=>array(),
			'resources'=>array()
			,'snippets'=>array()
			,'variables'=>array()
			,'commands'=>array()
			,'errors'=>array()
			);
		$this->parse();
	}

	/**
	 * Get resources data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getResources(){
		return $this->data['resources'];
	}

	/**
	 * Get methods data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getMethods(){
		return $this->data['methods'];
	}

	/**
	 * Get commands data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getCommands(){
		return $this->data['commands'];
	}

	/**
	 * Get errors data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getErrors(){
		return $this->data['errors'];
	}

	/**
	 * Get services data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getServices(){
		return $this->data['services'];
	}

	/**
	 * Get events data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getEvents(){
		return $this->data['events'];
	}

	/**
	 * Get snippets data
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getSnippets(){
		return $this->data['snippets'];
	}

	/**
	 * Parse xml files
	 *
	 * @since 0.1.0
	 */
	private function parse(){
		if(!file_exists($this->dir))
			return;

		$oDir=opendir($this->dir);
		while($file=readdir($oDir)){

			$fileExtension=pathinfo($file, PATHINFO_EXTENSION);
			if($fileExtension!='xml')
				continue;
			$dataXML=new XMLParser($this->dir.'/'.$file);
			$this->appendVariables($dataXML);
		}

		$oDir=opendir($this->dir);
		while($file=readdir($oDir)){

			$fileExtension=pathinfo($file, PATHINFO_EXTENSION);
			if($fileExtension!='xml')
				continue;
			$dataXML=new XMLParser($this->dir.'/'.$file);
			$this->appendData($dataXML);
		}

	}

	/**
	 * Parse xml node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendData(XMLParser $dataXML){

		foreach($dataXML->get() as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'action':
					$this->appendAction($xmlValue);
				break;
				case 'service':
					$this->appendService($xmlValue);
				break;
				case 'event':
					$this->appendEvent($xmlValue);
				break;
				case 'resource':
					$this->appendResource($xmlValue);
				break;
				case 'snippet':
					$this->appendSnippet($xmlValue);
				break;
				case 'command':
					$this->appendCommand($xmlValue);
				break;
				case 'error':
					$this->appendError($xmlValue);
				break;
				default:
				case 'variable':
				break;
				default:
					throw new InvalidConfigKeyException($xmlKey);
			}
		}
	}

	/**
	 * Parse variables node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @since 0.1.0
	 */
	private function appendVariables(XMLParser $dataXML){

		foreach($dataXML->get() as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'variable':
					$this->appendVariable($xmlValue);
				break;
			}
		}
	}

	/**
	 * Parse services node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendService($dataXML){
		$arguments=$this->getArguments($dataXML , array('name','class'));
		$config=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'config':
					$configData=$this->getArguments($xmlValue , array('enviorment'));
					if($configData['enviorment']==$this->enviorment->getName())
						$config=$this->appendConfig($xmlValue,$arguments);
				break;
				case 'argument':
					$serviceArguments[]=$this->appendArgument($xmlValue,$arguments);
				break;
				default:
					throw new InvalidConfigKeyException($xmlKey);
			}
		}

		$this->setServiceData($arguments['name'],$arguments+array('config'=>$config,'arguments'=>$serviceArguments));
	}

	/**
	 * Parse events node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendEvent($dataXML){
		$arguments=$this->getArguments($dataXML , array('bind','class','method'));
		$config=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'config':
					$configData=$this->getArguments($xmlValue , array('enviorment'));
					if($configData['enviorment']==$this->enviorment->getName())
						$config=$this->appendConfig($xmlValue,$arguments);
				break;
				default:
					throw new InvalidConfigKeyException($xmlKey);
			}
		}

		$this->setEventData($arguments['bind'],array('class'=>$arguments['class'],'method'=>$arguments['method'],'config'=>$config));
	}

	/**
	 * Parse resources node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendResource($dataXML){
		$arguments=$this->getArguments($dataXML , array('pattern','expire','path'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setResourceData($arguments['pattern'],$arguments['expire'],$arguments['path']);
	}

	/**
	 * Parse snippets node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendSnippet($dataXML){
		$arguments=$this->getArguments($dataXML , array('class','method'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setSnippetData($arguments['class'],$arguments['method']);
	}

	/**
	 * Parse commands node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendCommand($dataXML){
		$arguments=$this->getArguments($dataXML , array('class','method','name'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setCommandData($arguments['name'],$arguments['class'],$arguments['method']);
	}

	/**
	 * Parse errors node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendError($dataXML){
		$arguments=$this->getArguments($dataXML , array('pattern','presenter'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setErrorData($arguments['pattern'],$arguments['presenter']);
	}

	/**
	 * Parse variables node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendVariable($dataXML){
		$arguments=$this->getArguments($dataXML , array('name','value'),false);
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}
		$this->setVariableData($arguments['name'],$arguments['value']);
	}

	/**
	 * Parse configs node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendConfig($dataXML){
		return $this->getArguments($dataXML , array('enviorment'));
	}

	/**
	 * Parse arguments node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendArgument($dataXML){
		return $this->getArguments($dataXML , array('type'));
	}

	/**
	 * Parse actions node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @throws \InvalidConfigKeyException
	 * @since 0.1.0
	 */
	private function appendAction($dataXML){
		$arguments=$this->getArguments($dataXML , array('controller','method'));
		foreach($dataXML as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'route':
					$this->appendRoute($xmlValue,$arguments);
				break;
				case 'presenter':
					$this->appendPresenter($xmlValue,$arguments);
				break;
				default:
					$this->appendExtraAction($xmlKey,$xmlValue,$arguments);
			}
		}


	}

	/**
	 * Parse extra arguments for action
	 *
	 * @param string $xmlKey
	 * @param \Arbor\Parser\XML $dataXML
	 * @param array $action
	 * @since 0.1.0
	 */
	private function appendExtraAction($xmlKey , $dataXML , $action){
		$arguments=$this->getArguments($dataXML);

		$name=$action['controller'].":".$action['method'];
		$this->data['methods']+=array($name=>array());
		
		$this->data['methods'][$name]+=array('extra'=>array());
		$this->data['methods'][$name]['extra'][]=array($xmlKey=>$arguments);

		//TODO sprawdzić czy route path nie duplikuje się
	}

	/**
	 * Parse route node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @param array $action
	 * @since 0.1.0
	 */
	private function appendRoute($dataXML , $action){
		$arguments=$this->getArguments($dataXML , array('pattern'));

		$this->setActionData($action['controller'],$action['method']
			,array(
				'route'=>$arguments
			));

		//TODO sprawdzić czy route path nie duplikuje się
	}

	/**
	 * Parse presenter node
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @param array $action
	 * @since 0.1.0
	 */
	private function appendPresenter($dataXML , $action){
		$arguments=$this->getArguments($dataXML , array('class'));

		$this->setActionData($action['controller'],$action['method']
			,array(
				'presenter'=>$arguments
			));

	}

	/**
	 * Parse action data
	 *
	 * @param string $controller
	 * @param string $method
	 * @param array $data
	 * @since 0.1.0
	 */
	private function setActionData($controller,$method,$data){
		$name=$controller.":".$method;
		$this->data['methods']+=array($name=>array());
		
		$this->data['methods'][$name]+=$data;
	}

	/**
	 * Parse service data
	 *
	 * @param string $serviceName
	 * @param array $data
	 * @since 0.1.0
	 */
	private function setServiceData($serviceName,$data){
		$name=$serviceName;

		$this->data['services']+=array($name=>array());
		
		$this->data['services'][$name]+=$data;
	}

	/**
	 * Parse event data
	 *
	 * @param string $bind
	 * @param array $data
	 * @since 0.1.0
	 */
	private function setEventData($bind,$data){
		$this->data['events']+=array($bind=>array());
		
		$this->data['events'][$bind][]=$data;
	}

	/**
	 * Parse resource data
	 *
	 * @param string $pattern
	 * @param int $expire
	 * @param string $path
	 * @since 0.1.0
	 */
	private function setResourceData($pattern,$expire,$path){
		$this->data['resources'][]=array('pattern'=>$pattern,'expire'=>$expire,'path'=>$path);		
	}

	/**
	 * Parse snippert data
	 *
	 * @param string $class
	 * @param string $method
	 * @since 0.1.0
	 */
	private function setSnippetData($class,$method){
		$this->data['snippets'][$method]=$class;		
	}

	/**
	 * Parse command data
	 *
	 * @param string $name
	 * @param string $class
	 * @param string $method
	 * @since 0.1.0
	 */
	private function setCommandData($name,$class,$method){
		$this->data['commands'][$name]=array('class'=>$class,'method'=>$method);		
	}

	/**
	 * Parse error data
	 *
	 * @param string $pattern
	 * @param string $presenter
	 * @since 0.1.0
	 */
	private function setErrorData($pattern,$presenter){
		$this->data['errors'][$pattern]=$presenter;		
	}

	/**
	 * Parse variable data
	 *
	 * @param string $name
	 * @param string $value
	 * @since 0.1.0
	 */
	private function setVariableData($name,$value){
		$this->data['variables']['{'.$name.'}']=$value;
	}

	/**
	 * Get variable value
	 *
	 * @param string $value
	 * @return mixed
	 * @since 0.1.0
	 */
	private function getVariable($value){
		if(isset($this->data['variables'][$value]))
			return $this->data['variables'][$value];
		else
			return $value;
	}

	/**
	 * Get arguments data
	 *
	 * @param \Arbor\Parser\XML $dataXML
	 * @param array $requires
	 * @param boolean $encodeVariables
	 * @return array
	 * @since 0.1.0
	 */
	private function getArguments($dataXML,$requires=array(),$encodeVariables=true){
		$arguments=array();
		foreach($dataXML->attributes() as $argKey => $arg) {
			$value=(string)$arg[0];
			if($encodeVariables){
				$value=$this->getVariable($value);
			}
			$arguments[$argKey]=$value;
		}
		foreach ($requires as $require) {
			if(!isset($arguments[$require])){
				throw new ConfigAttributeNotFoundException($require);
			}
		}

		return $arguments;

	}

}
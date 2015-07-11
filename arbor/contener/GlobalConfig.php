<?php

namespace Arbor\Contener;

use Arbor\Parser\XML as XMLParser;
use Arbor\Exception\InvalidConfigKeyException;
use Arbor\Exception\ConfigAttributeNotFoundException;
use Arbor\Core\Enviorment;

class GlobalConfig{
	
	private $dir;
	private $data;
	private $enviorment;

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

	public function getResources(){
		return $this->data['resources'];
	}

	public function getMethods(){
		return $this->data['methods'];
	}

	public function getCommands(){
		return $this->data['commands'];
	}

	public function getErrors(){
		return $this->data['errors'];
	}

	public function getServices(){
		return $this->data['services'];
	}

	public function getEvents(){
		return $this->data['events'];
	}

	public function getSnippets(){
		return $this->data['snippets'];
	}

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

	private function appendVariables(XMLParser $dataXML){

		foreach($dataXML->get() as $xmlKey=>$xmlValue){
			switch($xmlKey){
				case 'variable':
					$this->appendVariable($xmlValue);
				break;
			}
		}
	}

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

	private function appendResource($dataXML){
		$arguments=$this->getArguments($dataXML , array('pattern','expire','path'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setResourceData($arguments['pattern'],$arguments['expire'],$arguments['path']);
	}

	private function appendSnippet($dataXML){
		$arguments=$this->getArguments($dataXML , array('class','method'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setSnippetData($arguments['class'],$arguments['method']);
	}

	private function appendCommand($dataXML){
		$arguments=$this->getArguments($dataXML , array('class','method','name'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setCommandData($arguments['name'],$arguments['class'],$arguments['method']);
	}

	private function appendError($dataXML){
		$arguments=$this->getArguments($dataXML , array('pattern','presenter'));
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}

		$this->setErrorData($arguments['pattern'],$arguments['presenter']);
	}

	private function appendVariable($dataXML){
		$arguments=$this->getArguments($dataXML , array('name','value'),false);
		$configs=array();
		$serviceArguments=array();
		foreach($dataXML as $xmlKey=>$xmlValue){
			throw new InvalidConfigKeyException($xmlKey);
		}
		$this->setVariableData($arguments['name'],$arguments['value']);
	}

	private function appendConfig($dataXML){
		return $this->getArguments($dataXML , array('enviorment'));
	}

	private function appendArgument($dataXML){
		return $this->getArguments($dataXML , array('type'));
	}

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

	private function appendExtraAction($xmlKey , $dataXML , $action){
		$arguments=$this->getArguments($dataXML);

		$name=$action['controller'].":".$action['method'];
		$this->data['methods']+=array($name=>array());
		
		$this->data['methods'][$name]+=array('extra'=>array());
		$this->data['methods'][$name]['extra'][]=array($xmlKey=>$arguments);

		//TODO sprawdzić czy route path nie duplikuje się
	}

	private function appendRoute($dataXML , $action){
		$arguments=$this->getArguments($dataXML , array('pattern'));

		$this->setActionData($action['controller'],$action['method']
			,array(
				'route'=>$arguments
			));

		//TODO sprawdzić czy route path nie duplikuje się
	}

	private function appendPresenter($dataXML , $action){
		$arguments=$this->getArguments($dataXML , array('class'));

		$this->setActionData($action['controller'],$action['method']
			,array(
				'presenter'=>$arguments
			));

	}

	private function setActionData($controller,$method,$data){
		$name=$controller.":".$method;
		$this->data['methods']+=array($name=>array());
		
		$this->data['methods'][$name]+=$data;
	}

	private function setServiceData($serviceName,$data){
		$name=$serviceName;

		$this->data['services']+=array($name=>array());
		
		$this->data['services'][$name]+=$data;
	}

	private function setEventData($bind,$data){
		$this->data['events']+=array($bind=>array());
		
		$this->data['events'][$bind][]=$data;
	}

	private function setResourceData($pattern,$expire,$path){
		$this->data['resources'][]=array('pattern'=>$pattern,'expire'=>$expire,'path'=>$path);		
	}

	private function setSnippetData($class,$method){
		$this->data['snippets'][$method]=$class;		
	}

	private function setCommandData($name,$class,$method){
		$this->data['commands'][$name]=array('class'=>$class,'method'=>$method);		
	}

	private function setErrorData($pattern,$presenter){
		$this->data['errors'][$pattern]=$presenter;		
	}

	private function setVariableData($name,$value){
		$this->data['variables']['{'.$name.'}']=$value;
	}

	private function getVariable($value){
		if(isset($this->data['variables'][$value]))
			return $this->data['variables'][$value];
		else
			return $value;
	}

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
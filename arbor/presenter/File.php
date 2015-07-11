<?php

namespace Arbor\Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;
use Arbor\Exception\HeaderNotFoundException;

class File implements Presenter{

	public function render(RequestConfig $config , Response $response){

		header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());		
		foreach($response->getHeaders() as $name=>$value){
			header($name.': '.$value);
		}


		if($response->getStatusCode()<299){

			$startRange=0;
			$endRange=filesize($response->getContent())-1;

			try{
				$contentRange=$response->getHeader('Content-Range');

				if(preg_match('/^bytes ([0-9]+)-([0-9]+)\/([0-9]+)$/',$contentRange,$match)){
					$startRange=(int)$match[1];
					$endRange=(int)$match[2];

				}

			}
			catch(HeaderNotFoundException $e){
				//skipp
			}

			$buffer = 1024 * 8;
			$file = @fopen($response->getContent(), 'rb');
			fseek($file, $startRange);
			while(!feof($file) && ($p = ftell($file)) <= $endRange) {
			    if ($p + $buffer > $endRange) {
			        $buffer = $endRange - $p + 1;
			    }
			    set_time_limit(0);
			    echo fread($file, $buffer);
			    flush();
			}
			 
			fclose($file);
		}
	}
}
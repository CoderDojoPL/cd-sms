<?php

namespace Arbor\Controller;

use Arbor\Core\Controller;
use Arbor\Provider\Response;
use Arbor\Exception\HeaderNotFoundException;

class Resource extends Controller{

	public function download(){
		$request=$this->getRequest();

		$extra=$request->getExtra();
		$file=substr($request->getRoute(),1);
		if($extra[0]['path'] && preg_match('/^'.$extra[0]['pattern'].'$/',$request->getRoute(),$groups)){
			$file=substr($extra[0]['path'],1);
			for($i=1; $i < count($groups); $i++){
				$file=str_replace('{'.$i.'}',$groups[$i],substr($extra[0]['path'],1));
			}
		}
		$response=new Response();
		if(!is_readable($file)){ //nie znajduje pliku
			$response->setStatusCode(404);
		}
		else{

			$extension=substr(strrchr($file, "."), 1);
			$contentType="application/".$extension;
			switch($extension){
				case 'css':
				case 'html':
					$contentType='text/'.$extension;
					break;
				case 'js':
					$contentType='text/javascript';
					break;
				case 'flv':
					$contentType='video/x-flv';
					break;
				case 'mp4':
					$contentType='video/mp4';
					break;
				case 'jpg':
				case 'png':
				case 'gif':
					$contentType='image/'.$extension;
					break;
				}

				$fileSize=filesize($file);

				$expire=$this->getExpire($request->getExtra());

				$fileTimeModified=filemtime($file);
				$response->setHeader('content-type' , $contentType);
				$response->setHeader('content-length' , $fileSize);

				if($expire)
					$response->setExpire($expire);
				else
					$response->setHeader('Last-Modified' ,gmdate("D, d M Y H:i:s", $fileTimeModified)." GMT");


				$notModified=false;
				try{
					if(strtotime($request->getHeader('If-Modified-Since'))==$fileTimeModified){
						$notModified=true;
						$response->setStatusCode(304);
					}
				}
				catch(HeaderNotFoundException $e){
					//ignore
				}

				$this->rangeSupport($response,$fileSize);
				$response->setContent($file);				
		}



		return $response;
	}

	private function getExpire($extras){
		foreach($extras as $extra){
			foreach($extra as $key=>$value){
				if($key=='expire')
					return ($value==''?0:(int)$value);
			}
		}

		return 0;
	}

	private function rangeSupport($response,$fileSize){
		try{
			$request=$this->getRequest();
			$range=$request->getHeader('Range');
			$invalidRange=false;
			if(preg_match('/^bytes=([0-9]+)-([0-9]*)$/' ,$range,$match)){
				$startRange=$match[1];
				$endRange=$match[2];
				if($endRange=='' || $endRange>$filesize)
					$endRange=$fileSize-1;

				if($startRange>$endRange)
					$invalidRange=true;
				else{
					$response->setHeader('Accept-Ranges','0-'.$fileSize);
					$response->setHeader('Content-Range','bytes '.$startRange.'-'.$endRange.'/'.$fileSize);
					$response->setHeader('content-length' , $endRange-$startRange+1);
					$response->setStatusCode(206);
				}


			}
			else{
				$invalidRange=true;
			}


			if($invalidRange){
				$response->setStatusCode(416);
				$response->setHeader('Content-Range','bytes *-/'.$filesize);

			}

		}
		catch(HeaderNotFoundException $e){
			//skipp
		}

	}
}


?>
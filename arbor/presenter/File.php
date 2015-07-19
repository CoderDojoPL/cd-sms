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

namespace Arbor\Presenter;

use Arbor\Core\Presenter;
use Arbor\Provider\Response;
use Arbor\Contener\RequestConfig;
use Arbor\Exception\HeaderNotFoundException;

/**
 * Presenter for files.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
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
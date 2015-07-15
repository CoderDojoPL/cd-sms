<?php

namespace Arbor\Test;

class AnchorElement extends HTMLElement{
	

	public function click(){
		$href=$this->getAttribute('href');

		if($href!=''){
			$this->browser->loadPage($href);
		}
	}
}
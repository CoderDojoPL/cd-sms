<?php

namespace Common;

use Arbor\Component\Grid\GridDataManager;

class EmptyDataManager implements GridDataManager{

	/**
	 * {@inheritdoc}
	 */
	public function getRecords($limit,$page){
		$result=array();
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTotalCount(){
        return 0;
	}

}
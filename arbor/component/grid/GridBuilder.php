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

namespace Arbor\Component\Grid;

use Arbor\Component\Grid\GridFormatter;
use Arbor\Component\Grid\GridDataManager;
use Arbor\Component\Grid\BasicColumnFormatter;

/**
 * Generator grid. Support for mapping data, pagination and generate html code.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.17.0
 */
class GridBuilder{
	private $formatter;
	private $dataManager;
	private $limit=10;
	private $page=1;
	private $columns=array();
	private $sortColumn;

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param \Arbor\Component\Grid\GridFormatter $formatter
	 * @since 0.17.0
	 */
	public function setFormatter(GridFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param \Arbor\Component\Grid\GridDataManager $dataManager
	 * @since 0.17.0
	 */
	public function setDataManager(GridDataManager $dataManager){
		$this->dataManager=$dataManager;
	}

	/**
	 * Get DataManager
	 *
	 * @return \Arbor\Component\Grid\GridDataManager
	 * @since 0.18.0
	 */
	public function getDataManager(){
		return $this->dataManager;
	}

	/**
	 * Get columns data
	 *
	 * @return array
	 * @since 0.18.0
	 */
	public function getColumns(){
		return $this->columns;
	}

	/**
	 * Get records
	 *
	 * @return array
	 * @since 0.18.0
	 */
	public function getRecords(){
		return $this->dataManager->getRecords($this->limit,$this->page);
	}

	/**
	 * Get total count records
	 *
	 * @return int
	 * @since 0.18.0
	 */
	public function getTotalCount(){
		return $this->dataManager->getTotalCount();
	}

	/**
	 * Set limit records on single page
	 *
	 * @param int $limit - items on page
	 * @since 0.17.0
	 */
	public function setLimit($limit){
		$this->limit=$limit;
	}

	/**
	 * Set current page
	 *
	 * @param int $page - current page
	 * @since 0.17.0
	 */
	public function setPage($page){
		$this->page=$page;
	}

	public function setSortColumn($index){
		$this->sortColumn=$index;
	}

	/**
	 * Add column
	 *
	 * @param string $label - column name
	 * @param string|array $keys - mapped keys for record
	 * @since 0.17.0
	 */
	public function addColumn($label,$keys,$formatter=null){
		if(!$formatter){			
			$formatter=new BasicColumnFormatter();
		}

		if(!is_array($keys)){
			$keys=array($keys);
		}
		$this->columns[]=array('label'=>$label,'keys'=>$keys,'formatter'=>$formatter);
	}

	/**
	 * Generate html grid string
	 *
	 * @return string with html form
	 * @since 0.17.0
	 */
	public function render(){

		return $this->formatter->render($this->columns,$this->dataManager->getRecords($this->limit,$this->page)
			,$this->dataManager->getTotalCount(),$this->limit,$this->page,$this->sortColumn);
	}


	/**
	 * @since 0.17.0
	 */
	public function __toString(){
		return $this->render();
	}
}

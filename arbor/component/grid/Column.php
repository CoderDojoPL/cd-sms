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

use Arbor\Component\Grid\ColumnFormatter;
use Arbor\Component\Grid\BasicColumnFormatter;

/**
 * Interface of column formatter (cell in grid).
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.21.0
 */
class Column{

	/**
	 * List of id.
	 *
	 * @var array $keys
	 */
	private $keys;

	/**
	 * Column label/name.
	 *
	 * @var string $label
	 */
	private $label;

	/**
	 * Class with pattern to generate html.
	 *
	 * @var ColumnFormatter $formatter
	 */
	private $formatter;

	/**
	 * List of sort id.
	 *
	 * @var array $sortKeys
	 */
	private $sortKeys;

	/**
	 * Constuctor
	 *
	 * @param mixed $keys list of id for column or one element
	 * @param string $label
	 * @param ColumnFormatter $formatter class with template to format column
	 * @param array $sortKeys sort by list of id
	 * @since 0.22.0
	 */
	public function __construct($keys,$label,ColumnFormatter $formatter=null,$sortKeys=null){
		if(!is_array($keys)){
			$keys=array($keys);
		}

		$this->keys=$keys;

		$this->label=$label;

		if(!$formatter){
			$formatter=new BasicColumnFormatter();
		}

		$this->setFormatter($formatter);
		if($sortKeys===null){
			$sortKeys=$keys;
		}
		$this->setSortBy($sortKeys);
	}

	/**
	 * Set formatter class
	 *
	 * @param ColumnFormatter $formatter class with template to format column
	 * @since 0.22.0
	 */
	public function setFormatter(ColumnFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set sortable column by Keys
	 *
	 * @param miaxed $sortKeys sort by list of id
	 * @since 0.22.0
	 */
	public function setSortBy($sortKeys){

		if($sortKeys==null){
			$sortKeys=array();
		}

		if(!is_array($sortKeys)){
			$sortKeys=array($sortKeys);
		}
		$this->sortKeys=$sortKeys;
	}

	/**
	 * 
	 * @return array
	 * @since 0.22.0
	 */
	public function getSortKeys(){
		return $this->sortKeys;
	}

	/**
	 * Check sortable column
	 *
	 * @return boolean
	 * @since 0.22.0
	 */
	public function isSortable(){
		return !empty($this->sortKeys);
	}

	/**
	 * Get label of column
	 *
	 * @return string
	 * @since 0.22.0
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * Get column formatter
	 *
	 * @return ColumnFormatter
	 * @since 0.22.0
	 */
	public function getFormatter(){
		return $this->formatter;
	}

	/**
	 * Get column keys
	 *
	 * @return array
	 * @since 0.22.0
	 */
	public function getKeys(){
		return $this->keys;
	}

}
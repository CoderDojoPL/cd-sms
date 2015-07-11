<?php

namespace Arbor\Component\Grid;

interface GridDataManager{

	/**
	 * Get records for single page
	 * @param int $limit - count record on page
	 * @param int $page - current page
	 * @return array - records
	 * @since 0.17.0
	 */
	public function getRecords($limit,$page);

	/**
	 * Get total count records
	 * @return int - total count records
	 * @since 0.17.0
	 */
	public function getTotalCount();
	
}
<?php

namespace Arbor\Component\Grid;

interface GridFormatter{

	/**
	 * Method generated html grid
	 * @param array $columns - columns name
	 * @param array $records - records list
	 * @param int $totalCount - max records count
	 * @param int $limit - count records on page
	 * @param int $page - current pag number
	 * @since 0.17.0
	 */
	public function render($columns,$records,$totalCount,$limit,$page);

}
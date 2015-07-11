<?php

namespace Arbor\Component\Grid;

interface ColumnFormatter{

	/**
	 * Method generated html for column
	 * @param mixed $data - field from record
	 * @since 0.17.0
	 */
	public function render($data);

}
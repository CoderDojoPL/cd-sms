<?php

namespace Arbor\Component\Grid;

use Arbor\Component\Grid\ColumnFormatter;

class BasicColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		return htmlspecialchars($data);
	}

}
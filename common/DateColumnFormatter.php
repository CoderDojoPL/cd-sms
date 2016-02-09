<?php

/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common;

use Arbor\Component\Grid\ColumnFormatter;

/**
 * Formatter for GridBuilder
 *
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class DateColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		return $data[0]->format('y-m-d');
	}

}
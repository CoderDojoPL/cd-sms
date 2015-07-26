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
 * Formatter for grid column with logs success data
 *
 * @package Common
 * @author Michal Tomczak (m.tomczak@coderdojo.org.pl)
 */
class LogSuccessColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		if($data[0]){
			return 'Yes';
		}
		else{
			return 'No: '.$data[1];			
		}
	}

}
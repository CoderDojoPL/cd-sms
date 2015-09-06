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

/**
 * Formatter for GridBuilder
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.17.0
 */
class BasicColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		return htmlspecialchars(trim(implode(' ',$data)));
	}

}
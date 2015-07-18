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
 * Formatter for image column in grid
 * @package Common
 * @author Slawomir Nowak (s.nowak@coderdojo.org.pl)
 */
class ImageColumnFormatter implements ColumnFormatter
{

	/**
	 * {@inheritdoc}
	 */
	public function render($data)
	{
		$html = "";
		if (is_file(ltrim($data, '/'))) {
			$html = '<a href="' . $data . '" target="_blank" data-toggle="lightbox"><img src="' . $data . '" width="50" /></a>';
		}

		return $html;

	}

}
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

namespace Arbor\Core;

/**
 * Provider for session.
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
interface SessionProvider{

	/**
	 * Get session value.
	 *
	 * @param string $key
	 * @return mixed
	 * @since 0.1.0
	 */
	public function get($key);

	/**
	 * Set session value.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @since 0.1.0
	 */
	public function set($key,$value);

	/**
	 * Remove session value.
	 *
	 * @param string $key
	 * @since 0.1.0
	 */
	public function remove($key);

	/**
	 * Remove all session values.
	 *
	 * @since 0.1.0
	 */
	public function clear();

}
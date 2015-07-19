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
 * Interface for request
 *
 * @author Michal Tomczak (michal.tomczak@arborphp.com)
 * @since 0.1.0
 */
interface RequestProvider{

	/**
	 * Get uploaded file
	 * @arg name - field name
	 * @return Arbor\Core\FileUploaded
	 * @since 0.12.0
	 */
	public function getFile($name);

	public function getConfig();
	
	public function getUrl();

	public function getType();

	public function getHeader($name);

	public function getBody();

	public function setArgument($name,$value);

	public function getArguments();

	public function removeArgument($index);

	public function getRoute();

	public function getSession();

	public function getClass();

	public function getController();

	public function setController($controller);

	public function getMethod();

	public function setMethod($method);

	public function getPresenter();

	public function getExtra();

	public function getData();

	public function getQuery();

	public function getHost();

	public function getProtocol();

	public function isSSL();

	public function isAjax();

	public function getClientIp();

	public function isFullUploadedData();

}
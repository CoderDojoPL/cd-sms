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
	 * Get uploaded file.
	 *
	 * @param string $name field name
	 * @return \Arbor\Core\FileUploaded
	 * @since 0.12.0
	 */
	public function getFile($name);

	/**
	 * Get config.
	 *
	 * @return \Arbor\Contener\RequestConfig
	 * @since 0.1.0
	 */
	public function getConfig();
	
	/**
	 * Get http url.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getUrl();

	/**
	 * Get http method (POST,PUT,GET,DELETE).
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getType();

	/**
	 * Get header value.
	 *
	 * @param string $name header name
	 * @return string
	 * @throws \Arbor\Exception\HeaderNotFoundException
	 * @since 0.1.0
	 */
	public function getHeader($name);

	/**
	 * Get request body.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getBody();

	/**
	 * Set method of controller argument.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @since 0.1.0
	 */
	public function setArgument($name,$value);

	/**
	 * Get method of controller arguments.
	 *
	 * @param array
	 * @since 0.1.0
	 */
	public function getArguments();

	/**
	 * Remove method of controller argument.
	 *
	 * @param string $name
	 * @since 0.1.0
	 */
	public function removeArgument($name);

	/**
	 * Route config.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getRoute();

	/**
	 * Get session provider.
	 *
	 * @return \Arbor\Core\SessionProvider
	 * @since 0.1.0
	 */
	public function getSession();

	/**
	 * Get controller class name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getClass();

	/**
	 * Get controller name
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getController();

	/**
	 * Set controller name
	 *
	 * @param string $controller
	 * @since 0.1.0
	 */
	public function setController($controller);

	/**
	 * Get controller method name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getMethod();

	/**
	 * Set controller method name.
	 *
	 * @param string $method
	 * @since 0.1.0
	 */
	public function setMethod($method);

	/**
	 * Get presenter.
	 *
	 * @return \Arbor\Core\Presenter
	 * @since 0.1.0
	 */
	public function getPresenter();

	/**
	 * Get extra config action.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getExtra();

	/**
	 * Get request POST data.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getData();

	/**
	 * Get request GET data.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getQuery();

	/**
	 * Get host name.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getHost();

	/**
	 * Get server protocol (HTTP/1.1).
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getProtocol();

	/**
	 * Check is security connect to server.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSSL();

	/**
	 * Check is ajax request (xml http request)
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isAjax();

	/**
	 * Get client ip.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getClientIp();

	/**
	 * Detect for uploaded big post data on server. If return false then propably file and other post data not uploaded.
	 *
	 * @return boolean
	 * @since 0.18.0 
	 */
	public function isFullUploadedData();

}
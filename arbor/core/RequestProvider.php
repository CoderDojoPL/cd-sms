<?php

namespace Arbor\Core;


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
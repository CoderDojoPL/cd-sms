<?php

namespace Arbor\Core;

interface SessionProvider{

	public function get($key);
	public function set($key,$value);
	public function remove($key);
	public function clear();

}
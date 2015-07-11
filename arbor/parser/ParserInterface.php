<?php
namespace Arbor\Parser;

interface ParserInterface extends \Iterator{
	public function getValue($key);
	public function loadFromFile($path);
	public function loadFromString($data);
}
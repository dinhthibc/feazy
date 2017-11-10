<?php
namespace Feazy\Common;

class Configuration
{
	private $filename = 'feazy.ini';
	private $configs = false;

	public function __construct($filename) {
		$this->filename = $filename;
		if (file_exists($filename)) {
			$this->configs = parse_ini_file($filename, true);
		}
	}

	public function get($name) {
		if (isset($this->configs[$name])) {
			return $this->configs[$name];
		}

		return null;
	}

	public function getAll() {
		if (isset($this->configs)) {
			return $this->configs;
		}

		return array();
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: dinhthibc
 * Date: 10/28/17
 * Time: 7:54 PM
 */

namespace Feazy\Common;


class Translation
{
	private $rootPath = '';
	private $strings = [];
	private $default = 'vi';
	private $name = '';
	public function __construct($rootPath) {
		$this->rootPath = $rootPath;
	}

	public function _($translateKey) {
		if (isset($this->strings[$translateKey])) {
			return $this->strings[$translateKey];
		}
		return $translateKey;
	}

	public function setDefault($name) {
		$this->default = $name;
	}

	public function setLanguage($name) {
		$this->name = $name;
	}

	public function getLanguage() {
		return $this->name;
	}

	public function isDefault() {
		return $this->default == $this->name;
	}

	public function addResource(array $strings) {
		$this->strings = $this->strings + $strings;
	}

	public function load($name) {
		$filename = $this->rootPath . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR .  $name . '.php';
		if (file_exists($filename)) {
			$strings = require $filename;
			$this->addResource($strings);
		}
	}
}
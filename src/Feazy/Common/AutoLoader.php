<?php
namespace Feazy\Common;


class AutoLoader
{
	public function __construct($rootPath) {
		$this->register($rootPath);
	}

	private function register($rootPath) {
		spl_autoload_register(function($className) use ($rootPath) {
			$parts = explode('\\', $className);
			$filename = $rootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
			if (file_exists($filename)) {
				require_once $filename;
			}
		});
	}
}
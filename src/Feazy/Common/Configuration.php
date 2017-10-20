<?php
namespace Feazy\Common;

class Configuration
{
	private static $filename = 'feazy.ini';
	private static $configs = false;
	const ENV_KEY = 'env';

	private static function parseFile() {
		if (!self::$configs) {
			self::$configs = parse_ini_file(self::$filename, true);
		}
	}

	public static function setIniFilename($filename) {
		self::$filename	= $filename;
	}

	public static function get($name) {
		self::parseFile();

		if (isset(self::$configs[self::$configs[self::ENV_KEY]][$name])) {
			return self::$configs[self::$configs[self::ENV_KEY]][$name];
		}

		return null;
	}

	public static function getAll() {
		self::parseFile();

		if (isset(self::$configs[self::$configs[self::ENV_KEY]])) {
			return self::$configs[self::$configs[self::ENV_KEY]];
		}

		return array();
	}
}
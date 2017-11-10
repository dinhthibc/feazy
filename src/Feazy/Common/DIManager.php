<?php
/**
 * Created by PhpStorm.
 * User: dinhthibc
 * Date: 10/28/17
 * Time: 8:29 PM
 */

namespace Feazy\Common;


class DIManager
{
	private static $components = [];

	public static function add($key, $component) {
		self::$components[$key] = $component;
	}

	public static function get($key) {
		if (isset(self::$components[$key])) {
			return self::$components[$key];
		}
		return false;
	}

	public static function getComponents() {
		return self::$components;
	}
}
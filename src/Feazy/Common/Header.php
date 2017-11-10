<?php
/**
 * Created by PhpStorm.
 * User: dinhthibc
 * Date: 11/6/17
 * Time: 12:10 PM
 */

namespace Feazy\Common;

if (!function_exists('getallheaders'))
{
	function getallheaders()
	{
		$headers = '';
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

class Header
{
	private $headers = array();

	public function __construct() {
		$this->headers = getallheaders();
	}

	public function getAll() {
		return $this->headers;
	}

	public function get($key) {
		if (isset($this->headers[$key])) {
			return $this->headers['$key'];
		}

		return null;
	}
}
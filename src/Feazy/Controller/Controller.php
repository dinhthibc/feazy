<?php
/**
 * Created by PhpStorm.
 * User: dinhthibc
 * Date: 10/14/17
 * Time: 9:29 AM
 */

namespace Feazy\Controller;

use Feazy\Common\DIManager;

class Controller
{
	public function __construct() {
		$components = DIManager::getComponents();
		foreach ($components as $key => $component) {
			$this->{$key} = $component;
		}
	}

	public function toJSON($data) {
		@header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function success() {
		$this->toJSON(array(
			'success' => true
		));
	}

	public function error($message, $code = '') {
		http_response_code(400);
		$this->toJSON(array(
			'error_message' => $message,
			'error_code' => $code
		));
	}

	protected function redirect($uri) {
		header('Location: ' . $uri);
	}
}
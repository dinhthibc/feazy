<?php
/**
 * Created by PhpStorm.
 * User: dinhthibc
 * Date: 10/14/17
 * Time: 9:29 AM
 */

namespace Feazy\Controller;
use Feazy\Common\View;

class Controller
{
	/***
	 * @var View
	 */
	protected $view;
	public function __contruct() {
		$this->view = View::getInstance();
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
		$this->toJSON(array(
			'error_message' => $message,
			'error_code' => $code
		));
	}
}
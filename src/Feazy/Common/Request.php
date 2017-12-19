<?php
namespace Feazy\Common;

class Request {
	private $url = '';
	private $params = array();
	private $requestBodyParams = false;
	private $posts = array();
	private $parameters = array();
	public function __construct($url = '') {
		$this->url = $url;
		$this->params   = explode('/', $url);
		if (isset($_POST)) {
			$this->posts = $_POST;
		}

		$tmp = explode('?', $_SERVER['QUERY_STRING']);
		parse_str($tmp[count($tmp) - 1], $urlParams); //use the last question mark as delimiter
		$this->parameters = $urlParams;
	}

	public function setPosts($posts) {
		$this->posts = $posts;
	}

	public function setParameters($parameters) {
		$this->parameters = $parameters;
	}

	public function setBodyParams($params) {
		$this->requestBodyParams = $params;
	}

	/**
	 * Get all POST request
	 * @return array
	 */
	public function getPosts(){
		return $this->posts;
	}

	/**
	 * Get all GET request
	 * @return array
	 */
	public function getParameters(){
		return $this->parameters;
	}

	/**
	 * Get POST with index = $name
	 * If not found then return $defaultValue
	 * @param $name
	 * @param null $defaultValue
	 * @return null
	 */
	public function getPost($name, $defaultValue = null){
		if (isset ($this->posts[$name]))
			return $this->posts[$name];
		return $defaultValue;
	}

	public function getBodyParams() {
		if (!$this->requestBodyParams) {
			$input = file_get_contents('php://input');
			parse_str($input, $params);
			$this->requestBodyParams = $params;
			return $this->requestBodyParams;
		}
		return array();
	}

	public function getBodyParam($key) {
		if (isset($this->requestBodyParams[$key])) {
			return $this->requestBodyParams[$key];
		}
		return false;
	}

	/**
	 * Get GET with index = $name
	 * If not found then return $defaultValue
	 * @param $name
	 * @param null $defaultValue
	 * @return null
	 */
	public function getParameter($name, $defaultValue = null){
		if (isset ($this->parameters[$name]))
			return $this->parameters[$name];
		return $defaultValue;
	}


	/**
	 * Return list of url array
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}

	/**
	 * Get full url
	 * @return mixed
	 */
	public function getURL(){
		return $this->url;
	}
}
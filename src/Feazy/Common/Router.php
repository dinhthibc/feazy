<?php
namespace Feazy\Common;

class Router {
	private $prefix = 'Application\\Controller\\';

	private $route = '';
	private $ns = '';

	public function __construct($route = '/') {
		$this->route = trim($route, '/');
	}

	private function dispatch($regex, $callback, $method = false) {
		if ($method && strtolower($_SERVER['REQUEST_METHOD']) != strtolower($method)) {
			return;
		}

		if (!is_callable($callback)) {
			$callback = explode('@', $callback);
			$callback[0] = $this->prefix . $callback[0];
		}

		$regex = $this->ns . $regex;
		$regex = trim($regex, '/');
		$regex = str_replace('/', '\/', $regex);
		$is_match = preg_match('/^' . ($regex) . '$/', $this->route, $matches);
		if ($is_match && is_callable($callback)) {
			$this->execute($callback, array_slice($matches, 1));
		}
	}

	private function execute($callback, $parameters = array()) {
		if (is_array($callback)) {
			$obj = new $callback[0]();
			call_user_func_array(array($obj, $callback[1]), $parameters);
		} else {
			call_user_func_array($callback, $parameters);
		}
		exit;
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

	public function matchPrefix($prefix, callable $callback)
	{
		$prefix = trim($prefix, '/');
		$route = $this->route;
		if (strpos($route, ltrim($prefix, "/")) === 0) {
			$route = str_replace($prefix, '', $route);
			$this->route = trim($route, '/');
			call_user_func_array($callback, array());
			return true;
		}
		return false;
	}

	public function group($ns, callable $callback) {
		if (is_callable($callback)) {
			$ns = $this->ns . $ns;
			if (strpos($this->route, ltrim($ns, "/")) === 0) {
				$this->ns = $ns;
				$callback($this);
				$this->ns = '';
			}
		}
	}

	public function any($regex, $callback) {
		$this->dispatch($regex, $callback);
	}

	public function get($regex, $callback) {
		$this->dispatch($regex, $callback, 'get');
	}

	public function post($regex, $callback) {
		$this->dispatch($regex, $callback, 'post');
	}

	public function put($regex, $callback) {
		$this->dispatch($regex, $callback, 'put');
	}

	public function delete($regex, $callback) {
		$this->dispatch($regex, $callback, 'delete');
	}

	public function basicRoute() {
		$route = str_replace(trim($this->ns, '/'), '', $this->route);
		$route = trim($route, '/');
		$routePaths = explode('/', $route);

		$controllerName = 'Index';
		if (isset($routePaths[0]) && $routePaths[0] != '') {
			$controllerName = ucfirst($routePaths[0]);
		}

		$controller = $this->prefix . $controllerName . 'Controller';
		$method = 'index';
		$paramStartIndex = 1;
		if (isset($routePaths[1])) {
			if (is_callable(array($controller, $routePaths[1]))) {
				$method = $routePaths[1];
				$paramStartIndex = 2;
			}
		}

		$parameters = array_slice($routePaths, $paramStartIndex);
		$callback = array(
			$controller, $method
		);
		if (is_callable($callback)) {
			$this->execute($callback, $parameters);
		}
	}

	public function notFound($callback) {
		if (!is_callable($callback)) {
			$callback = explode('@', $callback);
			$callback[0] = $this->prefix . $callback[0];
		}

		$this->execute($callback);
	}
}
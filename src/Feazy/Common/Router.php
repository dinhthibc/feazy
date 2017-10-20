<?php
namespace Feazy\Common;

class Router {
	private $prefix = 'Controller\\';

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
			if (is_array($callback)) {
				$obj = new $callback[0]();
				//$obj->$callback[1](...array_slice($matches, 1));
				call_user_func_array(array($obj, $callback[1]), array_slice($matches, 1));
			} else {
				//$callback(...array_slice($matches, 1));
				call_user_func_array($callback, array_slice($matches, 1));
			}
			exit;
		}
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
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
}
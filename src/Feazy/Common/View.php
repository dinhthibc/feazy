<?php
namespace Feazy\Common;

/**
 * Class View
 * @package Feazy\Common
 * @property Configuration config
 */
class View {
	protected $title = '';
	protected $layout = 'layout';
	protected $template = '';
	protected $headerScript = array();
	protected $headerStyle  = array();
	protected $script = array();
	protected $style = array();
	protected $data = array();

	public function __construct($template) {
		$this->template = $template;
		//append DI component
		$components = DIManager::getComponents();
		foreach ($components as $key => $component) {
			$this->{$key} = $component;
		}
	}

	public function addSection($section, $variables = array()) {
		$data = array();
		foreach ($variables as $variable) {
			if (isset($this->data[$variable])) {
				$data[$variable] = $this->data[$variable];
			}
		}
		extract($data, EXTR_OVERWRITE);

		$filename = $this->template . DIRECTORY_SEPARATOR . $section . '.phtml';
		if (file_exists($filename)) {
			require_once $filename;
		}
	}

	public function render($name, $data = array(), $single = false) {
		$this->data = $data;
		extract($data, EXTR_OVERWRITE);

		if (!$single){
			foreach ($this->headerScript as $value){
				$this->script[] = '<script src="' . $this->template . DIRECTORY_SEPARATOR  . $value . '"></script>';
			}
			foreach ($this->headerStyle as $value){
				$this->style[] = '<link rel="stylesheet" type="text/css" href="' . $this->template . DIRECTORY_SEPARATOR  . $value . '" />';
			}

			$this->content = $this->template . DIRECTORY_SEPARATOR . $name . '.phtml';

			$scriptFile = $this->template . DIRECTORY_SEPARATOR . $name . '.script.phtml';
			if (file_exists($scriptFile)) {
				$this->scriptFile = $scriptFile;
			} else {
				$this->scriptFile = false;
			}

			include(sprintf('%s/layout/%s.phtml', $this->template, $this->layout));
		} else{
			include($this->template . DIRECTORY_SEPARATOR . $name . '.phtml');
		}
	}

	public function setTitle($title, $replace = true){
		if ($replace){
			$this->title = $title;
		} else{
			$this->title = sprintf('%s | %s', $title, $this->title);
		}
	}

	public function setLayout($layoutName){
		$this->layout = $layoutName;
	}

	public function getTemplateURL() {
		if (isset($this->config) && $this->config->get('base_url')) {
			return $this->config->get('base_url') . $this->template;
		}
		return $this->template;
	}

	public function addScript($filename) {
		$this->headerScript[] = $filename;
	}

	public function addStyle($filename) {
		$this->headerStyle[] = $filename;
	}

	public function getHeaderScript() {
		return implode("\n", $this->script);
	}

	public function getHeaderStyle() {
		return implode("\n", $this->style);
	}
}
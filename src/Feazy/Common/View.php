<?php
namespace Feazy\Common;

class View {
	protected $title = '';
	protected $layout = 'layout';
	protected $template = '';
	protected $headerScript = array();
	protected $headerStyle  = array();
	protected $script = array();
	protected $style = array();

	private static $instance = null;
	public static function getInstance(){
		if (self::$instance == null){
			self::$instance = new View();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->title = Configuration::get('sitename');
		$this->template = Configuration::get('template') . '/';
	}

	public function render($name, $data = array(), $single = false) {
		if (!$single){
			foreach ($this->headerScript as $value){
				$this->script[] = '<script src="' . $this->getTemplateURL() . $value . '"></script>';
			}
			foreach ($this->headerStyle as $value){
				$this->style[] = '<link rel="stylesheet" type="text/css" href="' . $this->getTemplateURL() . $value . '" />';
			}

			extract($data, EXTR_OVERWRITE);

			$this->content = $this->template . $name . '.phtml';
			include($this->template . 'layout/' . $this->layout . '.phtml');
		} else{
			include($this->template . $name . '.phtml');
		}
	}

	public function setTitle($title, $replace = true){
		if ($replace){
			$this->title = $title;
		} else{
			$this->title = sprintf('%s - %s', $title, $this->title);
		}
	}

	public function setLayout($layoutName){
		$this->layout = $layoutName;
	}

	public function getTemplateURL(){
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
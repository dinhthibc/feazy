<?php

namespace Feazy\Database;

use Feazy\Common\Configuration;
use Feazy\Common\DIManager;

/**
 * Class MySQL
 * @package Feazy\Database
 * @property Configuration config
 */
class MySQL
{
	/***
	 * @var $DB \PDO
	 */
	protected $DB;
	/***
	 * @var $Slave \PDO
	 */
	protected $Slave;

	public function __construct() {
		$components = DIManager::getComponents();
		foreach ($components as $key => $component) {
			$this->{$key} = $component;
		}

		$this->connect();
	}

	public function __destruct() {
		$this->disconnect();
	}

	public function connect()
	{
		$this->DB = new PDOConnectionManager('mysql:host='. $this->config->get('db_host') .';dbname='. $this->config->get('db_name'), $this->config->get('db_user'), $this->config->get('db_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_PERSISTENT => true));
		if ($this->config->get('slave_host')) {
			$this->Slave = new PDOConnectionManager('mysql:host='. $this->config->get('slave_host') .';dbname='. $this->config->get('slave_name'), $this->config->get('slave_user'), $this->config->get('slave_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_PERSISTENT => true));
		} else {
			$this->Slave = $this->DB;
		}
	}

	public function disconnect()
	{
		$this->DB 		= null;
		$this->Slave	= null;
	}
}
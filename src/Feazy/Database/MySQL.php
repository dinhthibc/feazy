<?php

namespace Feazy\Database;

use Feazy\Common\Configuration;

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
		$this->connect();
	}

	public function __destruct() {
		$this->disconnect();
	}

	public function connect()
	{
		try {
			$this->DB = new PDOConnectionManager('mysql:host='. Configuration::get('db_host') .';dbname='. Configuration::get('db_name'), Configuration::get('db_user'), Configuration::get('db_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_PERSISTENT => true));
			if (Configuration::get('slave_host')) {
				$this->Slave = new PDOConnectionManager('mysql:host='. Configuration::get('slave_host') .';dbname='. Configuration::get('slave_name'), Configuration::get('slave_user'), Configuration::get('slave_password'), array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', \PDO::ATTR_PERSISTENT => true));
			}
		} catch (\PDOException $e) {

		}
	}

	public function disconnect()
	{
		$this->DB 		= null;
		$this->Slave	= null;
	}
}
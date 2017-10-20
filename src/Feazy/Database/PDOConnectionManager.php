<?php
namespace Feazy\Database;

class PDOConnectionManager
{
	private 		$dsn;
	private 		$username;
	private 		$passwd;
	private 		$options;
	/***
	 * @var \PDO
	 */
	private 		$dbh		= null;

	const RETRY_ATTEMPTS = 2;
	const CONNECTION_TIME_OUT = 1;

	public function __construct($dsn, $username, $passwd, $options = array())
	{
		$this->dsn 		= $dsn;
		$this->username = $username;
		$this->passwd 	= $passwd;
		$this->options 	= $options;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function beginTransaction() {
		$ret = false;

		$retry_attempt = 0;

		if ($this->dbh === null) {
			$this->connect();
		}

		while($retry_attempt <= self::RETRY_ATTEMPTS) {
			$has_gone_away = false;

			try {
				$ret = $this->dbh->beginTransaction();
				break;
			} catch (\PDOException $e) {
				$exception_message = $e->getMessage();

				if (strpos($exception_message, 'server has gone away') !== false
					&& $retry_attempt < self::RETRY_ATTEMPTS
				) {
					$has_gone_away = true;
				} else {
					throw $e;
				}
			}

			$retry_attempt++;
			$this->reconnect();
		}

		return $ret;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function commit() {
		return $this->dbh->commit();
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function errorCode() {
		return $this->dbh->errorCode();
	}

	/**
	 * @return array
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function errorInfo() {
		return $this->dbh->errorInfo();
	}

	/**
	 * @return int
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function exec($statement) {
		$ret = false;

		$retry_attempt = 0;

		if ($this->dbh === null) {
			$this->connect();
		}

		while($retry_attempt <= self::RETRY_ATTEMPTS) {
			$has_gone_away = false;

			try {
				$ret = $this->dbh->exec($statement);
				break;
			} catch (\PDOException $e) {
				$exception_message = $e->getMessage();

				if (strpos($exception_message, 'server has gone away') !== false
					&& $retry_attempt < self::RETRY_ATTEMPTS
				) {
					$has_gone_away = true;
				} else {
					throw $e;
				}
			}

			$retry_attempt++;
			$this->reconnect();
		}

		return $ret;
	}

	/**
	 * @param $attribute
	 * @return mixed
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function getAttribute(int $attribute) {
		return $this->dbh->getAttribute($attribute);
	}

	/**
	 * @return array
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public static function getAvailableDrivers() {
		return \PDO::getAvailableDrivers();
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function inTransaction() {
		return $this->dbh->inTransaction();
	}

	/**
	 * @param string $name
	 * @return string
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function lastInsertId($name = NULL) {
		return $this->dbh->lastInsertId();
	}

	/**
	 * @param string 	$statement
	 * @param array		$driver_options
	 * @return PDOStatement
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function prepare($statement, $driver_options = array()) {
		$ret = false;

		$retry_attempt = 0;

		if (!$this->dbh) {
			$this->connect();
		}

		while($retry_attempt <= self::RETRY_ATTEMPTS) {
			$has_gone_away = false;

			try {
				$ret = $this->dbh->prepare($statement, $driver_options);
				break;
			} catch (\PDOException $e) {
				$exception_message = $e->getMessage();

				if (strpos($exception_message, 'server has gone away') !== false
					&& $retry_attempt < self::RETRY_ATTEMPTS
				) {
					$has_gone_away = true;
				} else {
					throw $e;
				}
			}

			$retry_attempt++;
			$this->reconnect();
		}

		return $ret;
	}

	/**
	 * @param string $statement
	 * @return PDOStatement
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function query($statement) {
		$ret = false;

		$retry_attempt = 0;

		if ($this->dbh === null) {
			$this->connect();
		}

		while($retry_attempt <= self::RETRY_ATTEMPTS) {
			$has_gone_away = false;

			try {
				$ret = $this->dbh->query($statement);
				break;
			} catch (\PDOException $e) {
				$exception_message = $e->getMessage();

				if (strpos($exception_message, 'server has gone away') !== false
					&& $retry_attempt < self::RETRY_ATTEMPTS
				) {
					$has_gone_away = true;
				} else {
					throw $e;
				}
			}

			$retry_attempt++;
			$this->reconnect();
		}

		return $ret;
	}

	/**
	 * @param string 	$string
	 * @param int		$parameter_type
	 * @return string
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function quote($string, $parameter_type = \PDO::PARAM_STR) {
		$ret = false;

		$retry_attempt = 0;

		if ($this->dbh === null) {
			$this->connect();
		}

		while($retry_attempt <= self::RETRY_ATTEMPTS) {
			$has_gone_away = false;

			try {
				$ret = $this->dbh->quote($string, $parameter_type);
				break;
			} catch (\PDOException $e) {
				$exception_message = $e->getMessage();

				if (strpos($exception_message, 'server has gone away') !== false
					&& $retry_attempt < self::RETRY_ATTEMPTS
				) {
					$has_gone_away = true;
				} else {
					throw $e;
				}
			}

			$retry_attempt++;
			$this->reconnect();
		}

		return $ret;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function rollBack() {
		return $this->dbh->rollBack();
	}

	/**
	 * @param int 		$attribute
	 * @param mixed		$value
	 * @return bool
	 * @throws \Exception
	 * @throws \PDOException
	 */
	public function setAttribute($attribute, $value) {
		if (!$this->dbh) {
			$this->connect();
		}
		return $this->dbh->setAttribute($attribute, $value);
	}

	/**
	 * Connects to DB
	 */
	private function connect()
	{
		$this->dbh = new \PDO($this->dsn, $this->username, $this->passwd, $this->options);
		/*
		 * I am manually setting to catch error as exception so that the connection lost can be handled.
		 */
		$this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->dbh->setAttribute(\PDO::ATTR_TIMEOUT, self::CONNECTION_TIME_OUT);
	}

	/**
	 * Reconnects to DB
	 */
	private function reconnect()
	{
		$this->dbh = null;
		$this->connect();
	}
}
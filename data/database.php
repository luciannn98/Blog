<?php

/**
 * 
 */
class Database
{
	private static $dbHost = DBHOST;
	private static $dbName = DBNAME;
	private static $dbUser = DBUSER;
	private static $dbPass = DBPASS;

	protected $result;

	// public function __construct() {
	// }

	protected static function connect() {
		$pdo = new PDO("mysql:host=".self::$dbHost."; dbname=".self::$dbName.";charset=utf8", self::$dbUser, self::$dbPass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}

	protected function syntax($query, $params = []) {
		$st = self::connect()->prepare($query);
		if ($st->execute($params)) {
			if (explode(' ', $query)[0] == 'SELECT') {
				if ($st->rowCount() > 0) {
					$this->result = $st->fetchAll(PDO::FETCH_ASSOC);
					return $this->result;
				} else {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public function getUserNickFromID($id) {
		$auth = is_array($this->syntax("SELECT `auth` FROM `accounts` WHERE `id` = ? LIMIT 1", [$id])[0]) ? implode('', $this->syntax("SELECT `auth` FROM `accounts` WHERE `id` = ? LIMIT 1", [$id])[0]) : $this->syntax("SELECT `auth` FROM `accounts` WHERE `id` = ? LIMIT 1", [$id])[0];

		//var_dump($auth);
		return $auth;
	}

	public function getIDFromNick($nick) {
		$id = is_array($this->syntax("SELECT `id` FROM `accounts` WHERE `auth` = ? LIMIT 1", [$nick])[0]) ? implode('', $this->syntax("SELECT `id` FROM `accounts` WHERE `auth` = ? LIMIT 1", [$nick])[0]) : $this->syntax("SELECT `id` FROM `accounts` WHERE `auth` = ? LIMIT 1", [$nick])[0];

		//var_dump($id);
		return $id;
	}

	public function checkAccount($id) {
		if (!empty($id)) {
			$id_ip = implode('', $this->syntax("SELECT `user_ip` FROM `accounts` WHERE `id` = ? LIMIT 1", [$id])[0]);
			$user_ip = getUserIP();
			//var_dump($id_ip);
			if ($user_ip != $id_ip) {
				die();
			} else {
				return true;
			}
		}
	}
}

if (isset($_POST['search'])) {
	header('location: http://'.WEB_URL.'/home/search/'.$_POST['data']['search']);
}

function getUserIP() {
	switch(true) {
		case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
		case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
		case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
		default : return $_SERVER['REMOTE_ADDR'];
	}
}

function imageResize($imageResourceId,$width,$height) {
    $targetWidth =750;
    $targetHeight = 300;

    $targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);
    imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);

    return $targetLayer;
}
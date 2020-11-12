<?php

/**
 * 
 */
class Account extends Database {
	protected $data_input;
	protected $inventory = [];
	public $error;

	public function __construct() {
		$this->checkAccount(isset($_SESSION['account']['id'])?$_SESSION['account']['id']:0);
		$this->Login();
		if (!empty($this->data_input)) {
			$this->data_input = array_map("htmlspecialchars", array_map("strip_tags", $this->data_input));
		}
		//var_dump($this->data_input);
	}

	private function loadSQL($params) {
		//var_dump($params);
		if ($this->syntax("SELECT `id`, `auth`, `account_type` FROM `accounts` WHERE `email` = ? AND `password` = ?", $params)) {
			$this->inventory = $this->syntax("SELECT `id`, `auth`, `account_type` FROM `accounts` WHERE `email` = ? AND `password` = ?", $params);
			return true;
		} else {
			return false;
		}
	}

	private function loadSQLv2($sql, $params = []) {
		if (empty($params)) {
			$this->inventory = $this->syntax($sql);
		} else {
			$this->inventory = $this->syntax($sql, $params);
		}
	}

	private function insertSQL($params) {
		return $this->syntax("INSERT INTO `admins` (auth, `password`) VALUES (?, ?);", $params);
	}

	public function getNickFromID($id) {
		return $this->getUserNickFromID($id);
	}

	public function getCategory() {
		$this->loadSQLv2("SELECT * FROM `blog_categorii` ORDER BY `category_position` ASC");
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function Login() {
		if (isset($_POST['send'])) {
			$this->data_input = $_POST['data'];
			list($email, $password) = $this->data_input;
			$password = hash('sha1', $password);
			$this->data_input[1] = $password;
			//var_dump($this->data_input);

			if($this->loadSQL($this->data_input)) {
				$this->error = ['success', 'You are now logged in.'];
				unset($this->inventory[0]['password']);
				$_SESSION['account'] = $this->inventory[0];
			} else {
				$this->error = ['danger', 'E-mail or password is worng.'];
			}
		}
	}
}
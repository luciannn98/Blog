<?php

/**
 * 
 */
class Contact extends Database {
	protected $data_input;
	protected $inventory = [];
	public $error;

	public function __construct() {
		$this->checkAccount(isset($_SESSION['account']['id'])?$_SESSION['account']['id']:0);
		$this->getData();
		if (!empty($this->data_input)) {
			$this->data_input = array_map("htmlspecialchars", array_map("strip_tags", $this->data_input));
		}
	}

	private function loadSQL($sql, $params = []) {
		if (empty($params)) {
			$this->inventory = $this->syntax($sql);
			if (isset($this->inventory[0]['auth'])) {
				for($i=0;$i<count($this->inventory);$i++) {
					$this->inventory[$i]['auth'] = $this->getUserNickFromID($this->inventory[$i]['auth']);
				}
			}
		} else {
			$this->inventory = $this->syntax($sql, $params);
			if (isset($this->inventory[0]['auth'])) {
				for($i=0;$i<count($this->inventory);$i++) {
					$this->inventory[$i]['auth'] = $this->getUserNickFromID($this->inventory[$i]['auth']);
				}
			}
		}
	}

	public function getCategory() {
		$this->loadSQL("SELECT * FROM `blog_categorii` ORDER BY `category_position` ASC");
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getData() {
		if (isset($_POST['send'])) {
			$this->data_input = $_POST['data'];

			list($name, $email, $subject, $message) = $this->data_input;

			$checkEmail = explode("@", $email);
			$checkEmail = explode(".", $checkEmail[1]);

			if ($checkEmail[0] == 'yahoo') {
				$to = ADMIN_EMAIL; 
				$email_subject = $subject;
				$email_body = "
				<h4>Ai fost contactat de catre ".$name.", acesta dorind sa ia legatura cu tine.</h4><br>
				<br>
				<b>Email ".$name."</b>: " . $email . "<br><br>
				<b>Mesaj</b>: " . $message;
				$headers = 'Content-type: text/html; charset=iso-8859-1' . "\n"; 
				mail($to, $email_subject, $email_body, $headers);
				$this->error = ['succes', 'Email-ul a fost trimis cu succes.'];
			} else {
				$this->error = ['danger', 'Adresa de email trebuie sa fie Yahoo Mail.'];
			}
		}
	}
}
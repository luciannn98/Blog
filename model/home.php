<?php

/**
 * 
 */
class Home extends Database {
	protected $data_input;
	protected $inventory = [];
	public $error;

	public function __construct() {
		$this->checkAccount(isset($_SESSION['account']['id'])?$_SESSION['account']['id']:0);
		$this->getData();
		if (!empty($this->data_input)) {
			$this->data_input = array_map("htmlspecialchars", array_map("strip_tags", $this->data_input));
		}
		//var_dump($_POST);
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

	private function insertSQL($sql, $params) {
		return $this->syntax($sql, $params);
	}

	public function getCategory() {
		$this->loadSQL("SELECT * FROM `blog_categorii` ORDER BY `category_position` ASC");
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getPosts($params = null, $type = null) {
		$limit = isset($_POST['newCount']) ? $_POST['newCount'] : 10;
		$categ = isset($_POST['category']) ? $_POST['category'] : $params;
		if ($type == null) {
			$this->loadSQL("SELECT * FROM `blog_posts` ORDER BY `id` DESC LIMIT $limit;");
		} elseif ($type == 'str') {
			$params = '\'%'.$params.'%\'';
			$this->loadSQL("SELECT * FROM `blog_posts` WHERE `title` LIKE $params OR `message` LIKE $params LIMIT 50;");
		} elseif ($type == 'int') {
			$this->loadSQL("SELECT * FROM `blog_posts` WHERE `category` = $categ ORDER BY `id` DESC LIMIT $limit");
		}
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getPost($params) {
		$this->loadSQL("SELECT * FROM `blog_posts` WHERE `id` = ?", $params);
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getComentarii($params) {
		$this->loadSQL("SELECT * FROM `blog_comentarii` WHERE `post_id` = ?", $params);
		if (!empty($this->inventory)) {
			return $this->inventory;
		}
	}

	public function getPostTitle($params) {
		$this->loadSQL("SELECT `title`, `message`, `auth` FROM `blog_posts` WHERE `id` = ?", $params);
		if (!empty($this->inventory)) {
			//var_dump($this->inventory);
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getAuth($id) {
		return $this->getUserNickFromID($id);
	}

	public function getAuthID($id) {
		return $this->getIDFromNick($id);
	}

	public function getData() {
		if (isset($_POST['send'])) {
			$this->data_input = $_POST['data'];
			$data = [
				$this->data_input['id'],
				$_SESSION['account']['auth'],
				$this->data_input['message'],
				time()
			];
			//var_dump($data);
			
			if($this->insertSQL("INSERT INTO `blog_comentarii` (`post_id`, `auth`, `message`, `post_time`) VALUES (?, ?, ?, ?)", $data)) {
				$this->error = ['success', 'Comentariu postat cu succes.'];
			} else {
				$this->error = ['danger', 'Eroare SQL, incearca mai tarziu.'];
			}
		}
	}
}
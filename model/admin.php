<?php

/**
 * 
 */
class Admin extends Database {
	protected $data_input;
	protected $inventory = [];
	public $error;

	public function __construct() {
		$this->checkAccount(isset($_SESSION['account']['id'])?$_SESSION['account']['id']:0);
		$this->getData();
		if (!empty($this->data_input)) {
			$this->data_input = array_map("htmlspecialchars", array_map("strip_tags", $this->data_input));
		}
		//var_dump($this->data_input);
	}

	private function loadSQL($sql, $params = []) {
		if (empty($params)) {
			$this->inventory = $this->syntax($sql);
		} else {
			$this->inventory = $this->syntax($sql, $params);
		}
	}

	private function insertSQL($sql, $params) {
		return $this->syntax($sql, $params);
	}

	public function getPosts() {
		$this->loadSQL("SELECT `id`, `title` FROM `blog_posts` ORDER BY `id` DESC LIMIT 100;");
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getUsers() {
		$this->loadSQL("SELECT `id`, `auth` FROM `accounts` ORDER BY `id` DESC LIMIT 100;");
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}

	public function getPostImage($id = null) {
		$this->loadSQL("SELECT `image` FROM `blog_posts` WHERE `id` = ?;", $id);
		if (!empty($this->inventory)) {
			return $this->inventory;
		} else {
			$this->error = ['danger', 'No data found.'];
		}
	}


	public function deletePost($params = '') {
		//var_dump(getcwd());
		unlink("upload/".$this->getPostImage([$params])[0]["image"]);
		if ($this->insertSQL("DELETE FROM `blog_posts` WHERE `id` = ?", [$params])) {
			$this->error = ['succes', 'Post a fost sters cu succes.'];
		} else {
			$this->error = ['danger', 'Eroare SQL, incearca din nou mai tarziu.'];
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
			$image = $_FILES['file'];
			$extension = pathinfo($image["name"], PATHINFO_EXTENSION);
			list($imageWidth, $imageHeight, $imageType) = getimagesize($image["tmp_name"]);
			$fileName = time()."-".rand(0000,9999).'.'.$extension;
			$folderPath = "upload/";

			if ($imageWidth > 750 && $imageHeight > 300) {

				switch ($imageType) {
					case IMAGETYPE_PNG:
						$imageResourceId = imagecreatefrompng($image["tmp_name"]); 
						$targetLayer = imageResize($imageResourceId, $imageWidth, $imageHeight);
						imagepng($targetLayer,$folderPath.$fileName);
						break;
					case IMAGETYPE_GIF:
						$imageResourceId = imagecreatefromgif($image["tmp_name"]); 
						$targetLayer = imageResize($imageResourceId, $imageWidth, $imageHeight);
						imagegif($targetLayer,$folderPath.$fileName);
						break;
					case IMAGETYPE_JPEG:
						$imageResourceId = imagecreatefromjpeg($image["tmp_name"]); 
						$targetLayer = imageResize($imageResourceId, $imageWidth, $imageHeight);
						imagejpeg($targetLayer,$folderPath.$fileName);
						break;
					default:
						$this->error = ['danger', 'Imagine invalida, incearca o imagine PNG/JPEG/GIF.'];
						break;
				}
			} else {
				move_uploaded_file($image["tmp_name"], $folderPath.$fileName);
			}

			$data = [
				$_SESSION['account']['id'],
				time(),
				$this->data_input['title'],
				$this->data_input['message'],
				$fileName,
				$this->data_input['category']
			];
			
			if($this->insertSQL("INSERT INTO `blog_posts` (`auth`, `post_time`, `title`, `message`, `image`, `category`) VALUES (?, ?, ?, ?, ?, ?)", $data)) {
				$this->error = ['success', 'Ai adaugat cu succes o noua noutate.'];
			} else {
				$this->error = ['danger', 'Eroare SQL, incearca din nou mai tarziu.'];
			}
		}
	}
}
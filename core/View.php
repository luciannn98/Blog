<?php
/**
 * 
 */
class View {
	protected $view_file;
	protected $view_data;

	public function __construct($view_file, $view_data) {
		$this->view_file = $view_file;
		$this->view_data = $view_data;
		//var_dump($this->getAction());
	}

	public function render() {
		if (file_exists(VIEW.$this->view_file.'.phtml')) {
			$link = explode(DIRECTORY_SEPARATOR, $this->view_file);
			//var_dump($link);
			require VIEW.'tamplate'.DIRECTORY_SEPARATOR.'header.phtml';
			include VIEW.$this->view_file.'.phtml';
			if ($link[0] != 'admin') {
				require VIEW.'tamplate'.DIRECTORY_SEPARATOR.'footer.phtml';
			} else {
				if ($link[1] != 'index') {
					require VIEW.'tamplate'.DIRECTORY_SEPARATOR.'footer.phtml';
				}
			}
		} else {
			include VIEW.'tamplate'.DIRECTORY_SEPARATOR.'404.phtml';
		}
	}

	public function renderLoader() {
		if (file_exists(VIEW.$this->view_file.'.phtml')) {
			include VIEW.$this->view_file.'.phtml';
		} else {
			include VIEW.'tamplate'.DIRECTORY_SEPARATOR.'404.phtml';
		}
	}

	public function getAction() {
		return (explode(DIRECTORY_SEPARATOR, $this->view_file)[1]);
	}
}

function SEO($text) {
	$litereNumereSpatiiCratime = '/[^\s\pN\pL]+/u';
	$spatiiDublicariCratime = '/[\-\s]+/';

	$text = preg_replace($litereNumereSpatiiCratime, '', mb_strtolower($text, 'UTF-8'));
	$text = preg_replace($spatiiDublicariCratime, '-', $text);
	$text = trim($text, '-');
	$text = trim($text, 'p');

	return $text;
}

function hashTag($text) {
	$array = explode(' ', $text);
	for($i = 0;$i < count($array);$i++) {
		if (substr($array[$i], 0, 1) === '#') {
			$array[$i] = '<span class="red">'.$array[$i].'</span>';
		}
	}
	$text = implode(' ', $array);
	return $text;
}

function isLoggedIn() {
	return isset($_SESSION['account']) ? true : false;
}

function isAdmin() {
	if (isLoggedIn() === true && $_SESSION['account']['account_type'] == 1):
		return true;
	else:
		return false;
	endif;
}

function protectLogin($login = false) {
	if (!$login) {
		if (isLoggedIn() != false) {
			header('Location: '.WEB_URL);
		}
	}
}

function protectAdmin() {
	if (isAdmin() != true) {
		header('Location: '.WEB_URL);
	}
}
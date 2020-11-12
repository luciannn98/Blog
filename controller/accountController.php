<?php
/*
*
*/
class accountController extends Controller {
	public function index() {
		$this->model('account');
		$this->view('account\login', [
			'category' => $this->model->getCategory()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Login in your account';
		$this->view->render();
		//var_dump($this->model->getUsers());
	}

	public function profile($id = null) {
		$this->model('account');
		$this->view('account\profile', [
			'category' => $this->model->getCategory()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = $this->model->getNickFromID($id).'\'s profile';
		/*
		*@ Profile variable
		*/
		$this->view->profileAuth = $this->model->getNickFromID($id);
		$this->view->render();
	}

	public function logout() {
		session_destroy();
		unset($_SESSION['account']);
		header('Location: '.WEB_URL.'/home');
	}
}
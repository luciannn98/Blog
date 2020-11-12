<?php
/*
*
*/
class adminController extends Controller {
	public function index() {
		$this->model('admin');
		$this->view('admin\index', [
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts(),
			'users' => $this->model->getUsers()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}

	public function dtopic($id = null) {
		$this->model('admin');
		$this->view('admin\index', [
			'id' => $id,
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts()
		]);
		$this->model->deletePost($id);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}

	public function create() {
		$this->model('admin');
		$this->view('admin\topics\create', [
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts(),
			'users' => $this->model->getUsers()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}

	public function manage() {
		$this->model('admin');
		$this->view('admin\topics\manage', [
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts(),
			'users' => $this->model->getUsers()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}

	public function users() {
		$this->model('admin');
		$this->view('admin\users\users', [
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts(),
			'users' => $this->model->getUsers()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}

	public function web() {
		$this->model('admin');
		$this->view('admin\web\settings', [
			'category' => $this->model->getCategory(),
			'posts' => $this->model->getPosts(),
			'users' => $this->model->getUsers()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome back '.$_SESSION['account']['auth'];
		$this->view->render();
		protectAdmin();
		//var_dump($this->model->getUsers());
	}
}
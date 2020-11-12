<?php
/*
*
*/
class homeController extends Controller {
	public function index() {
		$this->model('home');
		$this->view('home', [
			'blog_post' => $this->model->getPosts(),
			'category' => $this->model->getCategory()
			//'widget' => $this->model->getWidget()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome to my Blog';
		$this->view->render();
		//var_dump($this->model->getUsers());
	}

	public function more($params = null, $type = null) {
		$this->model('home');
		$this->view('loading\home', [
			'blog_post' => $this->model->getPosts()
		]);
		$this->view->renderLoader();
	}

	public function category($id=null, $text=null) {
		$this->model('home');
		if (isset($text)) {
			$this->view('loading\home', [
				'id' => $id,
				'blog_post' => $this->model->getPosts($id, 'int')
			]);
			$this->view->error = $this->model->error;
			$this->view->pageTitle = 'Welcome to my Blog';
			$this->view->renderLoader();
		} else {
			$this->view('home', [
				'id' => $id,
				'blog_post' => $this->model->getPosts($id, 'int'),
				'category' => $this->model->getCategory()
				//'widget' => $this->model->getWidget()
			]);
			$this->view->error = $this->model->error;
			$this->view->pageTitle = 'Welcome to my Blog';
			$this->view->render();
		}
	}

	public function search($word='') {
		$this->model('home');
		$this->view('home', [
			'word' => $word,
			'blog_post' => $this->model->getPosts($word, 'str'),
			'category' => $this->model->getCategory()
			//'widget' => $this->model->getWidget()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Welcome to my Blog';
		$this->view->render();
	}

	public function read($id='') {
		$this->model('home');
		$this->view('read', [
			'id' => $id,
			'read_post' => $this->model->getPost([(int)$id]),
			'read_post_comentarii' => $this->model->getComentarii([(int)$id]),
			'category' => $this->model->getCategory()
		]);
		$this->view->getAuth = $this->model->getPostTitle([(int)$id])[0]['auth'];
		$this->view->getAuthID = $this->model->getAuthID($this->model->getPostTitle([(int)$id])[0]['auth']);
		//var_dump($this->model->getAuthID($this->model->getPostTitle([(int)$id])[0]['auth']));
		$this->view->error = $this->model->error;
		$this->view->metaTagTitle = SEO($this->model->getPostTitle([(int)$id])[0]['title']);
		$this->view->metaTagDescription = SEO($this->model->getPostTitle([(int)$id])[0]['message']);
		$this->view->metaTagAuth = $this->model->getPostTitle([(int)$id])[0]['auth'];
		$this->view->pageTitle = $this->model->getPostTitle([(int)$id])[0]['title'];
		$this->view->render();
		//var_dump($this->model->getComentariiReply([(int)$id]));
	}

	public function contact() {
		$this->model('contact');
		$this->view('contact', [
			'category' => $this->model->getCategory()
			//'widget' => $this->model->getWidget()
		]);
		$this->view->error = $this->model->error;
		$this->view->pageTitle = 'Contacteaza administratorul';
		$this->view->render();
		//var_dump($this->model->getUsers());
	}
}
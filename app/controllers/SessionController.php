<?php


class SessionController extends ControllerBase
{
	public function initialize(){
		$this->tag->setTitle('Sign Up/Sign In');
		parent::initialize();
	}

	public function indexAction(){
		if(!$this->request->isPost()){
			$this->tag->setDefault('email', 'zwz@163.com');
			$this->tag->setDefault('password', 'phalcon');
		}
	}

	private function _registerSession(Users $user){
		$this->session->set('auth',array(
			'id'    =>  $user->id,
			'name'  =>  $user->name
		));
	}

	public function startAction(){
		if($this->request->isPost()){
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');

			$user = Users::findFirst(array(
				"(email = :email: OR username = :email:) AND password = :password: AND active = 'Y'",
				'bind' => array('email'=>$email, 'password' => sha1($password))
			));

			if($user != false){
				$this->_registerSession($user);
				$this->flash->success('Welcome' . $user->name);
				return $this->forward('invoices/index');
			}

			$this->flash->error('Wrong email/password');
		}
		return $this->forward('session/index');
	}

	public function endAction(){
		$this->session->remove('auth');
		$this->flash->success('GoodBye!');
		return $this->forward('index/index');
	}
}
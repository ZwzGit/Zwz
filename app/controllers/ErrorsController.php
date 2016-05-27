<?php

class ErrorsController extends ControllerBase
{
	public function initialize(){
		$this->tag->setTitle('Welcome');
		parent::initialize();
	}

	public function indexAction(){
		if(!$this->request->isPost()){
			$this->flash->notice('This is ErrorController Part');
		}
	}

}
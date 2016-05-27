<?php

class CompaniesController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Welcome');
		parent::initialize();
	}

	public function indexAction()
	{
		if(!$this->request->isPost()){
			$this->flash->notice('This is companiesController part');
		}

	}

}
<?php

use Phalcon\Flash;
use Phalcon\Session;

class ProductsController extends ControllerBase
{
	public function initialize(){
		$this->tag->setTitle('Manage your products');
		parent::initialize();
	}

	public function indexAction(){
		$this->session->conditions = null;
		$this->view->form = new ProductsForm;
	}

	public function searchAction(){
		$numberPage = 1;
		if($this->request->isPost()){
			$query = Criteria::formInput($this->di, "Products", $this->request->getPost());
			$this->persistent->searchParams = $query->getParams();
		}else{
			$numberPage = $this->request->getQuery("page", "int");
		}

		$parameters = array();
		if($this->persistent->searchParams){
			$parameters = $this->persistents->searchParams;
		}

		$products = Products::find($parameters);
		if(count($products) == 0){
			$this->flash->notice('The search did not find any products');
			return $this->forward('products/index');
		}

		$paginator = new Paginator(array(
				'data'  =>  $products,
				'limit' =>  10,
				'page'  =>  $numberPage
		));

		$this->view->page = $paginator->getPaginate();
	}

	public function newAction(){
		$this->view->form = new ProductsForm(null,array('edit' => true));
	}

	public function editAction($id){
		if(!$this->request->isPost()){
			$product = Products::findFirstById($id);
			if(!$product){
				$this->flash->error('Product was not found');
				return $this->forward('products/index');
			}

			$this->view->form = new ProductsForm($product, array('edit' => true));
		}
	}

	public function createAction(){
		if(!$this->request->isPost()){
			return $this->forward('products/index');
		}

		$form = new ProductsForm;
		$product = new Products();

		$data = $this->request->getPost();
		if(!$form->isValid($data, $product)){
			foreach($form->getMessages() as $message){
				$this->flash->error((string) $message);
			}
			return $this->forward('products/new');
		}

		$form->clear();
		$this->flash->success('Product was created successfully');
		return $this->forward('products/index');
	}

	public function saveAction(){
		if(!$this->request->isPost()){
			return $this->forward('products/index');
		}

		$id = $this->request->getPost('id','int');

		$product = Products::findFirstById($id);
		if(!$product){
			$this->flash->error('Product does not exist');
			return $this->forward('products/index');
		}

		$form = new ProductsForm;
		$this->view->form = $form;

		$data = $this->request->getPost();

		if(!$form->isValid($data, $product)){
			foreach($form->getMessages()as $message){
				$this->flash->error((string) $message);
			}
			return $this->forward('products/edit/' . $id);
		}

		if($product->save() == false){
			foreach($product->getMessages() as $message){
				$this->flash->error($message);
			}
			return $this->forward('products/edit/' . $id);
		}

		$form->clear();
		$this->flash->success('Product was updated successfully');
		return $this->forward('products/index');
	}

	public function deleteAction($id){
		$products = Products::findFirstById($id);
		if(!$products){
			$this->flash->error('Product was not found');
			return $this->forward('products/index');
		}

		if(!$products->delete()){
			foreach($products->getMessages() as $message){
				$this->flash->error($message);
			}
			return $this->forward('products/search');
		}

		$this->flash->success('Product was deleted');
		return $this->forward('products/index');
	}
}
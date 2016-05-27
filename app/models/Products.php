<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
	public $id;

	public $product_types_id;

	public $name;

	public $price;

	public $active;

	public function initialize(){
		$this->belongTo('product_types_id' , 'ProductTypes', 'id', array('reusable' => true));
	}

	public function getActiveDetail(){
		if($this->active == 'Y'){
			return 'Yes';
		}else{
			return 'No';
		}
	}

}
<?php

use Phalcon\Mvc\Model;

class ProductTypes extends Model
{
	public $id;

	public $name;

	public function initialize(){
		$this->hasMany('id', 'Products', 'product_types_id', array(
			'foreignKey'    =>  array(
				'message'   =>  'Product Type cannot be deleted because it\'s useed in Products'
			)
		));
	}

}
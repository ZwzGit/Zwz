<?php

class Persons extends \Phalcon\Mvc\Model{

	protected $id;
	protected $name;

	public function initialize(){
		$this->setSource("the_persons");
	}

	// public function getSource(){
	// 	return "the_persons";
	// }
	public function getId(){
		return $this->id;
	}


}
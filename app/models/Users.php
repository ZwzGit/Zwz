<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends Model
{

	public function validation(){
		$this->validate(new EmailValidator(array(
			'filed' =>  'email'
		)));
		$this->validate(new UniquenessValidator(array(
			'filed' =>  'email',
			'message'   => 'Sorry,The email was registered by anther user'
		)));
		$this->validate(new UniquenessValidator(array(
			'filed' =>  'username',
			'message'   =>  'Sorry, That username is already taken'
		)));
		if($this->validationHasFailed() == true){
			return false;
		}
	}

}

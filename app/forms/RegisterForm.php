<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-04
 * Time: 13:00
 */
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class RegisterForm extends Form{
    public function initialize($entity = null, $options = null){
        $name = new Text('name');
        $name->setLabel('Your Full Name');
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message'   =>  'Name is required'
            ))
        ));
        $this->add($name);

        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message'   =>  'E-mail is required'
            )),
            new Email(array(
                'message'   =>  'E-mail is not valid'
            ))
        ));
        $this->add($email);

        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators(array(
            new PresenceOf(array(
                'message'   =>  'Password is required'
            ))
        ));
        $this->add($password);

        $repeatPassword = new Password('repeatPassword');
        $repeatPassword->setLabel('Repeat Password');
        $repeatPassword->addValidators(array(
            new PresenceOf(array(
                'message'   =>  'Confirmation password is required'
            ))
        ));
        $this->add($repeatPassword);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-04
 * Time: 12:47
 */
class RegisterController extends ControllerBase{
    public function initialize(){
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction(){
        $form = new RegisterForm;
        if($this->request->isPost()){
            $name = $this->request->getPost('name',array('string','striptags'));
            $username  = $this->request->getPost('username','alphanum');
            $email = $this->request->getPost('email','email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if($password !== $repeatPassword){
                $this->flash->error('Passwords are different');
                return false;
            }

            $user = new User();
            $user->username = $username;
            $user->password = shal($password);
            $user->name = $name;
            $user->email = $email;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->active = 'Y';
            if($user->save() == false){
                foreach($user->getMessage() as $message){
                    $this->flash->error((string) $message);
                }
            }else{
                $this->tag->setDefault('email', '');
                $this->tag->setDefault('password','');
                $this->flash->success('Thanks for sign-up,please log-in to start generating invoices');
                return $this->forward('session/index');
            }
        }
        $this->view->form = $form;
    }

}
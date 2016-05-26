<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-09
 * Time: 13:53
 */


class ContactController extends ControllerBase{

    public function initialize(){
        $this->tag->setTitle('Contact us');
        parent::initialize();
    }

    public function indexAction(){
        $this->view->form = new ContactForm;
    }

    public function sendAction(){
        if($this->request->isPost() != true){
            return $this->forward('contact/index');
        }
        $form  = new ContactForm;
        $contact = new Contact();

        $data = $this->request->getPost();
        if(!$form->isValid($data, $contact)){
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('contact/index');
        }

        if($contact->save() == false){
            foreach($contact->getMessages() as $message){
                $this->flash->error($message);
            }
            return $this->forward('contact/index');
        }
        $this->flash->success('Thanks, we will contact you in the next hours');
        return $this->forward('index/index');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-04
 * Time: 12:38
 */
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller{
    protected function initialize(){
        $this->tag->prependTitle('ZWZ | ');
        $this->view->setTemplateAfter('main');
    }

    protected function forward($uri){
        $uriParts = explode('/',$uri);
        $params = array_slice($uriParts,2);
        return $this->dispatcher->forward(
            array(
                'controller'    =>  $uriParts[0],
                'action'    =>  $uriParts[1],
                'parmas'    =>  $params
            )
        );
    }
}
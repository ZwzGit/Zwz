<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-03
 * Time: 14:29
 */
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class NotFoundPlugin extends Plugin{
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception){
        error_log($exception->getMessage().PHP_EOL.$exception->getTraceAsString());
        if($exception instanceof DispatcherException){
            switch($exception->getCode()){
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(array(
                        'controller'    =>  'errors',
                        'action'    =>  'show404'
                    ));
                    return false;
            }
        }
        $dispatcher->forward(array(
            'controller' =>  'errors',
            'action'    =>  'show500'
        ));
        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 0
 * Date: 2016-03-03
 * Time: 14:44
 */
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

class SecurityPlugin extends Plugin{

    public function getAcl(){
        if(!isset($this->persistent->acl)){
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);
            $roles = array(
                'users' =>  new Role('Users'),
                'guests'    =>  new Role('Guests')
            );
            foreach($roles as $role){
                $acl->addRole($role);
            }

            $privateResources = array(
                'companies' =>  array(
                    'index',
                    'search',
                    'new',
                    'edit',
                    'save',
                    'create',
                    'delete'
                ),
                'product'   =>  array(
                    'index',
                    'search',
                    'new',
                    'edit',
                    'save',
                    'create',
                    'delete'
                ),
                'producttypes'  =>  array(
                    'index',
                    'search',
                    'new',
                    'edit',
                    'save',
                    'create',
                    'delete'
                ),
                'invoices'  =>  array(
                    'index',
                    'search',
                    'new',
                    'edit',
                    'save',
                    'create',
                    'delete'
                ),
            );
            foreach($privateResources as $resource => $actions){
                $acl->addResource(new Resource($resource), $actions);
            }

            $publicResources = array(
                'index' =>  array('index'),
                'about' =>  array('index'),
                'register' =>  array('index'),
                'errors'    =>  array('show401','show404','show500'),
                'session'   =>  array('index','register','start','end'),
                'contact'   =>  array('index','send')
            );
            foreach($publicResources as $resource => $actions){
                $acl->addResource(new Resource($resource), $actions);
            }

            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action) {
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }

            $this->persistent->acl = $acl;
        }
        return $this->persistent->acl;
    }

    public function beforeDispatch(Event $event, Dispatcher $dispatcher){
        $auth = $this->session->get('auth');
        if(!$auth){
            $role = 'Guests';
        }else{
            $role = 'Users';
        }
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $acl = $this->getAcl();
        $allowed = $acl->isAllowed($role,$controller,$action);
        if($allowed != Acl::ALLOW ){
            $dispatcher->forward(array(
                'controller'    =>  'errors',
                'action'    =>  'show401'
            ));
            $this->session->destroy();
            return false;
        }
    }




}
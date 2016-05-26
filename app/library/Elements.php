<?php
use Phalcon\Mvc\User\Component;

class Elements extends Component{
	private $_headerMenu    =   array(
		'navbar-left'	=>	array(
            'index' =>  array(
                'caption'   =>  'Home',
                'action'    =>  'index'
            ),
            'invoices'  =>  array(
                'caption'   =>  'Invoices',
                'action'    =>  'index'
            ),
            'about' =>  array(
                'caption'   =>  'Contact',
                'action'    =>  'index'
            ),
            'contact'   =>  array(
                'caption'   =>  'Contact',
                'action'    =>  'index'
            ),
        ),
        'navbar-right'  =>  array(
            'session'   =>  array(
                'caption'   =>  'Log In/Sign Up',
                'action'    =>  'index'
            ),
        ),
	);

    private $_tabs = array(
        'Invoices'  =>  array(
            'controller'    =>  'invoices',
            'action'    =>  'index',
            'any'   =>  false,
        ),
        'Companies' =>  array(
            'controller'    =>  'companies',
            'action'    =>  'index',
            'any'   =>  false,
        ),
        'Products'  =>  array(
            'controller'    =>  'products',
            'action'    =>  'index',
            'any'   =>  false,
        ),
    );


    public function getMenu(){
        $auth = $this->session->get('auth');
        if($auth){
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption'   =>  'Log Out',
                'action'    =>  'end',
            );
        }else{
            unset($this->_headerMenu['navbar-left']['invoices']);
        }

        $controllerName = $this->view->getControllerName();
        foreach($this->_headerMenu as $position=> $menu){
            echo '<div class ="nav-collapse">';
            echo '<ul class="nav navbar-nav ', $position, '">';
            foreach($menu as $controller => $option){
                if($controllerName == $controller){
                    echo '<li class="active">';
                }else{
                    echo '<li>';
                }
                echo $this->tag->linkTo($controller.'/'.$option['action'], $option['caption']);
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }

    public function getTabs(){
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach($this->_tabs as $caption => $option){
            if($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])){
                echo '<li class="active">';
            }else{
                echo '<li>';
            }
            echo $this->tag->linkTo($option['controller'].'/'.$option['action'], $caption), '</li>';
        }
        echo '</ul>';
    }
}
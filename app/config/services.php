<?php
use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Falsh\Session as FalshSession;
use Phalcon\Events\Manager as EventsManager;

$di = new FactoryDefault();

$di->set('dispatcher',function () use ($di){
	$eventsManager = new EventsManager;
	$eventsManager->attach('dispatch:beforeDispatch',new SecurityPlugin);
	$eventsManager->attach('dispatch:beforeException',new NotFoundPLugin);

	$dispatcher = new Dispatcher;
	$dispatcher->setEventsManager($eventsManager);
	return $dispatcher;
});

$di->set('url',function () use ($config){
	$url = new UrlProvider();
	$url->setBaseUri($config->application->baseUri);
	return $url;
});

$di->set('view', function() use ($config){
	$view  = new View();
	$view->setViewsDir(APP_PATH.$config->application->viewsDir);
	$view->registerEngines(array(
		".volt"	=>	'volt'
	));
});

$di->set('volt', function($view, $di){
	$volt = new VoltEngine($view, $di);
	$volt->setOptions(array(
		"compiledPath"	=>	APP_PATH."cache/volt/"
	));
	$compiler = $volt->getCompiler();
	$compiler->addFunction('is_a','is_a');
	return $volt;
},true);

$di->set('db', function () use ($config){
	$config = $config->get('database')->toArray();
	$dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
	unset($config['adapter']);
	return new $dbClass($config);
});

$di->set('modelsMetadata', function(){
	return new MetaData();
});

$di->set('session', function(){
	$session = new SessionAdapter();
	$session->start();
	return $session;
});

$di->set('flash', function(){
	return new FalshSession(array(
		'error'		=>	'alert alert-danger',
		'success'	=>	'alert alert-success',
		'info'		=>	'alert alert-info',
		'warning'	=>	'alert alert-warning',
	));
});

$di->set('elements', function(){
	return new Elements();
});

